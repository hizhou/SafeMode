<?php
namespace PHPSafeMode\Rewriter\Convertor;

class FileInclude extends \PHPParser_NodeVisitorAbstract {
	public function beforeTraverse(array $nodes) {
		//var_dump($nodes);return ;
	}
	
	public function enterNode(\PHPParser_Node $node) {
	}
	
	public function leaveNode(\PHPParser_Node $node) {
		if ($node instanceof \PHPParser_Node_Expr_Include) {
			$args = array();
			$args[] = new \PHPParser_Node_Scalar_FileConst;
			$args[] = $node->expr;
			
			$node->expr = new \PHPParser_Node_Expr_FuncCall(
				new \PHPParser_Node_Name_FullyQualified(array('safemode_check_include')),
				$args
			);
			return $node;
		}
	}
	
	public function afterTraverse(array $nodes) {
	}
}