<?php
namespace PHPSafeMode\Tests\EPHPParser;

use PHPSafeMode\Tests\BaseTestCase;
use PHPSafeMode\Tests\CodeProvider;

class PrinterTest extends BaseTestCase {
	private $phpParser;
	
	public function test() {
		$mode = $this->getNewSafeMode();
		
		$specifies = array_merge($this->codeProvider()->findSpecifies('parser/*/*'), 
			$this->codeProvider()->findSpecifies('parser/*/*/*'),
			$this->codeProvider()->findSpecifies('prettyPrinter/*')
		);
		sort($specifies);
		
		foreach ($specifies as $specify) {
			$code = explode("\n-----", $this->codeProvider()->getCode($specify));
			$code = $this->filterCode($specify, trim($code[1]));
			if (null == $code) {continue;}
			
			$script = str_replace('/', '_', $specify) . '.php';
			$before = $this->runInOriginalMode($code, false, $script);
			
			$newCode = $this->getPrinter()->prettyPrint($this->getParser()->parse($code), $code) . '//new';
			$after = $this->runInOriginalMode($newCode, false, $script);
			
			$this->assertEquals($before, $after, 'happen in code: ' . $specify);
		}
	}
	
	private function filterCode($specify, $code) {
		$drops = array(
			'parser/stmt/function/generator',
			'parser/stmt/loop/for',
			'parser/stmt/class/trait',
			'parser/stmt/haltCompiler',
			'parser/stmt/namespace/outsideStmt',
		);
		if (in_array($specify, $drops)) {
			return null;
		}
		
		$filters = array(
			'parser/scalar/int' => array('@@{ PHP_INT_MAX     }@@;', '@@{ PHP_INT_MAX + 1 }@@;', '0b111000111000;'),
			'parser/scalar/float' => array('0b1111111111111111111111111111111111111111111111111111111111111111;'),
			'parser/stmt/blocklessStatement' => array('for (;;) $foo;'),
			'parser/stmt/tryCatch' => array(' finally {
    doFinally();
}', 'try { }
finally { }'),
		);
		if (isset($filters[$specify])) {
			$code = str_replace($filters[$specify], '', $code);
		}
		return $code . "\r\n" . "//" . $specify;
	}
	
	

	private function getPrinter() {
		return \PHPSafeMode_EPHPParser_Printer::getInstance();
	}
	
	private function getParser() {
		if (null === $this->phpParser) {
			$this->phpParser = new \PHPParser_Parser(new \PHPSafeMode_EPHPParser_Lexer());
		}
		return $this->phpParser;
	}
	
	
	
	protected function codeProvider() {
		if (!$this->codeProvider) {
			$this->codeProvider = new CodeProvider(
				realpath(__DIR__ . '/../../../../vendor/nikic/php-parser/test/code'), 
				'test'
			);
		}
		return $this->codeProvider;
	}
}
