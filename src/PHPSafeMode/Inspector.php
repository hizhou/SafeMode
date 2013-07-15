<?php
namespace PHPSafeMode;

class Inspector {
	private $originalCode;
	
	public function __construct($code) {
		$this->originalCode = $code;
	}
	
	public function disableFunctions($functions) {
		
	}
	
	public function disableClasses($classes) {
		
	}
	
	public function reImplementFunctions() {
		
	}
	
	public function reImplementClasses() {
		
	}
	
	public function setSafeBaseDir() {
		
	}
	
	
	
	/**
	 * 
	 * @param PHPParser_Node[] $nodes Array of nodes
	 * @return Ambigous <string, mixed>
	 
	public function outputCode($nodes) {
		$prettyPrinter = new \PHPParser_PrettyPrinter_Zend();
		$code = $prettyPrinter->prettyPrint($nodes);
		
		echo $code;
		return $code;
	}*/
	
	/**
	 *
	 * @return PHPParser_Node[]
	 
	public function convertMethodCall($code) {
		$traverser = new \PHPParser_NodeTraverser;
		$traverser->addVisitor(new MethodCallConvertor());
		
		return $traverser->traverse($this->parseCode($code));
	}*/
	
	
	
	private function parseCode($code) {
		$phpParser = new \PHPParser_Parser(new \PHPParser_Lexer());
		return $phpParser->parse($code);
	}
}
