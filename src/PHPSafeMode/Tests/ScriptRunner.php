<?php 
namespace PHPSafeMode\Tests;

class ScriptRunner {
	public function run($path) {
		if (!file_exists($path)) throw new \Exception('script not exists: ' . $path);
		
		/*
		$parts = pathinfo($path);
		$dir = $parts['dirname'];
		$file = $parts['basename'];
		
		exec("cd " . $dir);
		*/
		return exec("php -f " . $path);
	}
}