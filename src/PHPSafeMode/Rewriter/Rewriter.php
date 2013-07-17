<?php
namespace PHPSafeMode\Rewriter;

class Rewriter {
	private $originalCode;
	
	/**
	 * @var \PHPParser_NodeVisitorAbstract[]
	 */
	private $writers = array();
	
	
	public function __construct($code) {
		$this->originalCode = $code;
	}
	
	public function addWriter(\PHPParser_NodeVisitorAbstract $writer) {
		$this->writers[] = $writer;
	}
	
	public function generateCode() {
		$nodes = $this->parseCode($this->originalCode);
		
		if ($this->writers) {
			$traverser = new \PHPParser_NodeTraverser;
			foreach ($this->writers as $visitor) {
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
