<?php
namespace PHPSafeMode\Rewriter\Convertor;

class FileInclude extends BaseConvertor {
	private $functionName;
	
	public function __construct($functionName) {
		$this->functionName = $functionName;
	}
	
	public function beforeTraverse(array $nodes) {
	}
	
	public function enterNode(\PHPParser_Node $node) {
	}
	
	public function leaveNode(\PHPParser_Node $node) {
		if ($node instanceof \PHPParser_Node_Expr_Include) {
			$args = array();
			$args[] = new \PHPParser_Node_Scalar_FileConst;
			$args[] = $node->expr;
			
			$node->expr = new \PHPParser_Node_Expr_FuncCall(
				new \PHPParser_Node_Name_FullyQualified(array($this->functionName)),
				$args
			);

			$node->setAttribute('operate', 'replace');

			return $node;
		}
	}
	
	public function afterTraverse(array $nodes) {
	}
}