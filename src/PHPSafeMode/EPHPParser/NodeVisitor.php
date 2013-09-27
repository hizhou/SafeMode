<?php

class PHPSafeMode_EPHPParser_NodeVisitor extends PHPParser_NodeVisitorAbstract {
	private $tokens;
	private $newTokens = array();
	private $printer;
	
	public function __construct($code) {
		$this->tokens = @token_get_all($code);;
//var_dump($this->tokens);
		
		$this->printer = new PHPParser_PrettyPrinter_Default();
	}
	
	public function beforeTraverse(array $nodes) {
	}
	
	public function enterNode(\PHPParser_Node $node) {
	}
	
	public function leaveNode(\PHPParser_Node $node) {
		$startPos = $node->getAttribute('startPos');
		$endPos = $node->getAttribute('endPos');
		if (null === $startPos || null === $endPos) return ;
		
		if ($startPos !== $endPos) {
			return ;
		}
		
		$pos = $startPos;
		if (isset($this->newTokens[$pos])) return ;
		
		$this->tokens[$pos] = $this->printer->{'p' . $node->getType()}($node);
	}
	
	public function afterTraverse(array $nodes) {
		$str = '';
		foreach ($this->tokens as $token) {
			$str .= is_string($token) ? $token : $token[1];
		}
		echo $str;
		//var_dump($this->newTokens);
	}
}