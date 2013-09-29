<?php
namespace PHPSafeMode\Rewriter\Convertor;

class BaseConvertor extends \PHPParser_NodeVisitorAbstract {
	protected $namespace = '';
	
	protected function setCurrentNamespace(\PHPParser_Node $node) {
		if ($node instanceof \PHPParser_Node_Stmt_Namespace) {
			$this->namespace = $node->name->toString();
		}
	}
	
	protected function copyAttribute(\PHPParser_Node $to, \PHPParser_Node $from, array $attrs) {
		foreach ($attrs as $attr) {
			$to->setAttribute($attr, $from->getAttribute($attr));
		}
		return $to;
	}
}