<?php
namespace PHPSafeMode\Rewriter\Convertor;

class ClassMethodCall extends \PHPParser_NodeVisitorAbstract {
	private $functionName;
	private $alias = array();
	
	public function __construct($functionName) {
		$this->functionName = $functionName;
	}
	
	public function beforeTraverse(array $nodes) {
		//var_dump($nodes);return ;
	}
	
	public function enterNode(\PHPParser_Node $node) {
	}
	
	public function leaveNode(\PHPParser_Node $node) {
		/*if ($node instanceof \PHPParser_Node_Expr_MethodCall) {
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
		} */
		if ($node instanceof \PHPParser_Node_Expr_StaticCall) {
			if ($node->class instanceof \PHPParser_Node_Name) {
				$className = $node->class->toString();
				foreach ($this->alias as $k => $v) {
					if (strtolower($className) == strtolower($k)) {
						$className = $v;
						break;
					}
				}
				
				$name = new \PHPParser_Node_Scalar_String($className);
			} else {
				$name = $node->class;
			}
			
			$args = array();
			$args[] = $name;
			$args[] = is_string($node->name) 
				? new \PHPParser_Node_Scalar_String($node->name)
				: $node->name;

			foreach ($node->args as $arg) {
				$args[] = $arg;
			}

			$newNode = new \PHPParser_Node_Expr_FuncCall(
				new \PHPParser_Node_Name_FullyQualified(array($this->functionName)),
				$args
			);
			return $newNode;
		} elseif ($node instanceof \PHPParser_Node_Stmt_UseUse) {
			$this->alias[$node->alias] = $node->name->toString();
		}
	}
	
	public function afterTraverse(array $nodes) {
	}
}