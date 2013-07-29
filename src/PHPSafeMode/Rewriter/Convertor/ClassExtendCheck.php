<?php
namespace PHPSafeMode\Rewriter\Convertor;

class ClassExtendCheck extends \PHPParser_NodeVisitorAbstract {
	private $functionName;
	
	public function __construct($functionName) {
		$this->functionName = $functionName;
	}
	
	public function beforeTraverse(array $nodes) {
		//var_dump($nodes);return ;
	}
	
	public function enterNode(\PHPParser_Node $node) {
	}
	
	public function leaveNode(\PHPParser_Node $node) {
		if ($node instanceof \PHPParser_Node_Stmt_Class && $node->extends) {
			$args = array(
				new \PHPParser_Node_Scalar_String($node->extends->toString())
			);

			$newNode = new \PHPParser_Node_Expr_FuncCall(
				new \PHPParser_Node_Name_FullyQualified(array($this->functionName)),
				$args
			);
			return array($node, $newNode);
		}
	}
	
	public function afterTraverse(array $nodes) {
	}
}