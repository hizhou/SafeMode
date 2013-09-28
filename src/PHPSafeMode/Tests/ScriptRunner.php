<?php 
namespace PHPSafeMode\Tests;

class ScriptRunner {
	private $runCodeBasePath;
	
	public function __construct($runCodeBasePath) {
		$this->runCodeBasePath = rtrim($runCodeBasePath, '\\/');
	}
	
	public function run($path, $isDebug) {
		if (!file_exists($path)) throw new \Exception('script not exists: ' . $path);
		
//TODO TEST
$c = file_get_contents($path); if (strpos($c, '_NO_INDENT_')) {$this->debug($c);}
		$output = $this->runPhpScript($path);

		$result = implode("\r\n", $output);

		/* $fp = fopen(__DIR__ . '/data/log/log.txt', 'a+');
		fwrite($fp, $result);
		fclose($fp); */

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

	private function runPhpScript($path) {
		//exec("php -f " . $path, $output);
		$parts = pathinfo($path);

		$desc = array(
			1 => array("pipe", "w"),
			2 => array("pipe", "w"),
		);

		$process = proc_open("php -f " . $parts['basename'], $desc, $pipes, $parts['dirname']);
		if (is_resource($process)) {
			$stdout = stream_get_contents($pipes[1]);
			fclose($pipes[1]);

			$stderr = stream_get_contents($pipes[2]);
			fclose($pipes[2]);
			$result = proc_close($process);

			return array($stdout, $stderr, $result);
		}
		return array();
	}
}