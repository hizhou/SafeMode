<?php
namespace PHPSafeMode\Rewriter;

class Rewriter {
	private $originalCode;
	
	/**
	 * @var \PHPParser_NodeVisitorAbstract[]
	 */
	private $convertors = array();
	
	
	public function __construct($code) {
		$this->originalCode = $code;
	}
	
	public function addConvertor(\PHPParser_NodeVisitorAbstract $convertor) {
		$this->convertors[] = $convertor;
	}
	
	public function generateCode() {
		$nodes = $this->parseCode($this->originalCode);
		
		if ($this->convertors) {
			$traverser = new \PHPParser_NodeTraverser;
			foreach ($this->convertors as $visitor) {
				$traverser->addVisitor($visitor);
			}
			
			$nodes = $traverser->traverse($nodes);
		}
		
		$prettyPrinter = new \PHPParser_PrettyPrinter_Zend();
		return $prettyPrinter->prettyPrint($nodes);
	}
	
	
	
	private function parseCode($code) {
		$phpParser = new \PHPParser_Parser(new \PHPParser_Lexer());
		return $phpParser->parse($code);
	}
}
