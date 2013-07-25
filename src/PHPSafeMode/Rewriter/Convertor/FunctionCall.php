<?php
namespace PHPSafeMode\Rewriter\Convertor;

class FunctionCall extends \PHPParser_NodeVisitorAbstract {
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
		if ($node instanceof \PHPParser_Node_Expr_FuncCall) {
			if ($node->name instanceof \PHPParser_Node_Name) {
				$name = new \PHPParser_Node_Scalar_String($node->name->toString());
			} else {
				$name = $node->name;
			}
			
			$args = array();
			$args[] = $name;
			
			foreach ($node->args as $arg) {
				$args[] = $arg;
			}
			
			$newNode = new \PHPParser_Node_Expr_FuncCall(
				new \PHPParser_Node_Name_FullyQualified(array($this->functionName)),
				$args
			);
			return $newNode;
		}
	}
	
	public function afterTraverse(array $nodes) {
	}
}