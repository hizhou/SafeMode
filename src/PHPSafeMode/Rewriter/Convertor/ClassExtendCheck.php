<?php
namespace PHPSafeMode\Rewriter\Convertor;

class ClassExtendCheck extends BaseConvertor {
	private $functionName;
	private $alias = array();
	
	public function __construct($functionName) {
		$this->functionName = $functionName;
	}
	
	public function beforeTraverse(array $nodes) {
	}
	
	public function enterNode(\PHPParser_Node $node) {
		$this->setCurrentNamespace($node);
	}
	
	public function leaveNode(\PHPParser_Node $node) {
		if ($node instanceof \PHPParser_Node_Stmt_Class && $node->extends) {
			$isFully = ($node->extends instanceof \PHPParser_Node_Name_FullyQualified) ? true : false;
			$className = $node->extends->toString();
			foreach ($this->alias as $k => $v) {
				if (strtolower($className) == strtolower($k)) {
					$className = $v;
					break;
				}
			}
			
			$args = array(
				new \PHPParser_Node_Scalar_String($className),
				new \PHPParser_Node_Scalar_String($isFully ? '' : $this->namespace)
			);

			$newNode = new \PHPParser_Node_Expr_FuncCall(
				new \PHPParser_Node_Name_FullyQualified(array($this->functionName)),
				$args
			);
			$newNode->setAttribute('operate', 'append');
			$newNode->setAttribute('afterPos', $node->getAttribute('endPos'));
			return array($node, $newNode);
		} elseif ($node instanceof \PHPParser_Node_Stmt_UseUse) {
			$this->alias[$node->alias] = $node->name->toString();
		}
	}
	
	public function afterTraverse(array $nodes) {
	}
}
