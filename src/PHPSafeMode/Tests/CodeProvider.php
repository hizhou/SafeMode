<?php
namespace PHPSafeMode\Tests;

class CodeProvider {
	private $basePath;
	private $codeExt = 'php';
	
	public function __construct() {
		$this->basePath = __DIR__ . '/code';
	}
	
	public function getCode($specify) {
		$specify = trim($specify, '\\/');
		$file = $this->basePath . '/' . $specify . '.php';
		if (!file_exists($file)) throw new TestException('code file not exsits: ' . $file);
		
		return file_get_contents($file);
	}
	
	public function findSpecifies($pattern) {
		$pattern = trim($pattern, '\\/');
		
		$base = $this->basePath . '/';
		$ext = '.' . $this->codeExt;
		$extLen = strlen($ext);
		
		$specifies = array();
		foreach (glob($base . $pattern . $ext) as $file) {
			$file = str_replace($base, '', $file);
			$specifies[] = substr($file, 0, -1 * $extLen);
		}
		return $specifies;
	}
}