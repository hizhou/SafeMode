<?php
namespace PHPSafeMode\Rewriter\Convertor;

class PrependCode extends \PHPParser_NodeVisitorAbstract {
	private $prependNodes;

	public function __construct($prependNodes) {
		$this->prependNodes = $prependNodes;
	}

	public function beforeTraverse(array $nodes) {
	}

	public function enterNode(\PHPParser_Node $node) {
	}

	public function leaveNode(\PHPParser_Node $node) {
	}

	public function afterTraverse(array $nodes) {
		if ($nodes[0] instanceof \PHPParser_Node_Stmt_Namespace) {
			$nodes[0]->stmts = array_merge($this->prependNodes, $nodes[0]->stmts);
			return $nodes;
		} else {
			return array_merge($this->prependNodes, $nodes);
		}
	}
}