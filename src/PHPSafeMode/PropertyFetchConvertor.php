<?php
namespace PHPSafeMode;

class PropertyFetchConvertor extends \PHPParser_NodeVisitorAbstract {
	public function beforeTraverse(array $nodes) {
	}
	
	public function enterNode(\PHPParser_Node $node) {
	}
	
	public function leaveNode(\PHPParser_Node $node) {
		if ($node instanceof \PHPParser_Node_Expr_MethodCall) {
			if ($node->var->name == 'this') return ;
				
			$name = is_string($node->name) ? new \PHPParser_Node_Scalar_String($node->name) : $node->name;
				
			$args = array();
			$args[] = new \PHPParser_Node_Arg($name);
			$args[] = new \PHPParser_Node_Arg($node->var);
			foreach ($node->args as $arg) {
				$args[] = $arg;
			}
			$newNode = new \PHPParser_Node_Expr_FuncCall(
					new \PHPParser_Node_Name(array('call_user_method')),
					$args
			);
			return $newNode;
		} elseif ($node instanceof \PHPParser_Node_Expr_StaticCall) {
			; //TODO need to convert?
		}
	}
	
	public function afterTraverse(array $nodes) {
	}
}
