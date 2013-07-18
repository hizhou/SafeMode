<?php
namespace PHPSafeMode\Rewriter\Convertor;

class FunctionCall extends \PHPParser_NodeVisitorAbstract {
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
			$args[] = $name;//new \PHPParser_Node_Arg();
			
			//\PHPParser_Node_Expr;
			foreach ($node->args as $arg) {
				$args[] = $arg;
			}
			
			$newNode = new \PHPParser_Node_Expr_FuncCall(
				new \PHPParser_Node_Name(array('safemode_function_call')),
				$args
			);
			return $newNode;
		}
	}
	
	public function afterTraverse(array $nodes) {
	}
}