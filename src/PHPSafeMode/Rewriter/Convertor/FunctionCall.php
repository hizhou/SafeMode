<?php
namespace PHPSafeMode\Rewriter\Convertor;

class FunctionCall extends \PHPParser_NodeVisitorAbstract {
	private $functionName;
	private $namespace = '';
	
	public function __construct($functionName) {
		$this->functionName = $functionName;
	}
	
	public function beforeTraverse(array $nodes) {
		if ($nodes[0] instanceof \PHPParser_Node_Stmt_Namespace) {
			$this->namespace = $nodes[0]->name->toString();
		}
	}
	
	public function enterNode(\PHPParser_Node $node) {
	}
	
	public function leaveNode(\PHPParser_Node $node) {
		$special = '';
		if ($node instanceof \PHPParser_Node_Expr_Eval) $special = 'eval';
		if ($node instanceof \PHPParser_Node_Stmt_HaltCompiler) $special = '__halt_compiler';

		if ($special) {
			$newNode = new \PHPParser_Node_Expr_FuncCall(
				new \PHPParser_Node_Name_FullyQualified(array($this->functionName)),
				array(
					new \PHPParser_Node_Scalar_String($special),
				)
			);
			return $newNode;
		} elseif ($node instanceof \PHPParser_Node_Expr_FuncCall) {
			$isFully = ($node->name instanceof \PHPParser_Node_Name_FullyQualified) ? true : false;
			if ($node->name instanceof \PHPParser_Node_Name) {
				$name = new \PHPParser_Node_Scalar_String($node->name->toString());
			} else {
				$name = $node->name;
			}
			
			$args = array();
			$args[] = $name;
			$args[] = new \PHPParser_Node_Scalar_FileConst;
			$args[] = new \PHPParser_Node_Scalar_String($isFully ? '' : $this->namespace);
			
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