<?php 
namespace PHPSafeMode\Tests;

class ScriptRunner {
	private $runCodeBasePath;
	
	public function __construct($runCodeBasePath) {
		$this->runCodeBasePath = rtrim($runCodeBasePath, '\\/');
	}
	
	public function run($path, $isDebug) {
		if (!file_exists($path)) throw new \Exception('script not exists: ' . $path);
		
		$output = array();
		exec("php -f " . $path, $output);
		
		$result = implode("\r\n", $output);
		if ($isDebug) $this->debug($result);
		return $result;
	}
	
	public function runCode($code, $path = 'tmp.php', $isDebug) {
		$path = $this->runCodeBasePath . '/' . trim($path, '\\/');
		
		file_put_contents($path, $code);
		
		return $this->run($path, $isDebug);
	}
	
	private function debug($result) {
		echo "\r\n//----result---\r\n";
		echo $result;
		echo "\r\n-----------//\r\n";
	}
}