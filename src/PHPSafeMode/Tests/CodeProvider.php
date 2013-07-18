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
		if (!file_exists($file)) return ;
		return file_get_contents($file);
	}
}