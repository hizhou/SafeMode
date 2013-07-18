<?php
namespace PHPSafeMode\Tests;

class CodeProvider {
	private $basePath;
	private $codeExt = 'php';
	
	public function __construct() {
		$this->basePath = __DIR__ . '/code';
	}
	
	public function getCode($specify) {
		$file = $this->basePath . '/' . $specify . '.php';
		if (!file_exists($file)) throw new TestException('code file not exsits: ' . $file);
		
		return file_get_contents($file);
	}
}