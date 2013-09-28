<?php
namespace PHPSafeMode\Rewriter;

use PHPSafeMode\Rewriter\Convertor\PrependCode;

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
	
	public function generateCode($prependCodes = null) {
		$nodes = $this->parseCode($this->originalCode);

		if ($prependCodes) {
			$this->convertors[] = new PrependCode($this->parsePrependCodes($prependCodes));
		}
		
		if ($this->convertors) {
			$traverser = new \PHPParser_NodeTraverser;
			foreach ($this->convertors as $visitor) {
				$traverser->addVisitor($visitor);
			}
			
			$nodes = $traverser->traverse($nodes);
		}
		
		$prettyPrinter = new \PHPSafeMode_EPHPParser_Printer();
		return $prettyPrinter->prettyPrint($nodes, $this->originalCode);
		
		//$prettyPrinter = new \PHPParser_PrettyPrinter_Zend();
		//return $prettyPrinter->prettyPrint($nodes);
	}
	
	
	
	private function parseCode($code) {
		$phpParser = new \PHPParser_Parser(new \PHPSafeMode_EPHPParser_Lexer());
		return $phpParser->parse($code);
	}
	
	private function parsePrependCodes($codes) {
		if (!is_array($codes)) $codes = array($codes);
		
		$phpParser = new \PHPParser_Parser(new \PHPParser_Lexer());
		
		$nodes = array();
		foreach ($codes as $code) {
			foreach ($phpParser->parse($code) as $node) {
				$nodes[] = $node;
			}
		}
		return $nodes;
	}
}
