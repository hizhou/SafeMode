<?php
namespace PHPSafeMode\Rewriter\Convertor;

class PrependCode extends BaseConvertor {
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
			$this->setPrependNodesAttr($nodes[0]->stmts);
			$nodes[0]->stmts = array_merge($this->prependNodes, $nodes[0]->stmts);
			return $nodes;
		} else {
			$this->setPrependNodesAttr($nodes);
			return array_merge($this->prependNodes, $nodes);
		}
	}

	private function setPrependNodesAttr($preNodes) {
		$preNode = isset($preNodes[0]) ? $preNodes[0] : null;
		$afterPos = ($preNode ? $preNode->getAttribute('startPos') : 0) - 1;

		foreach ($this->prependNodes as $k => $v) {
			$this->prependNodes[$k]->setAttribute('operate', 'append');
			$this->prependNodes[$k]->setAttribute('afterPos', $afterPos);
		}
	}
}