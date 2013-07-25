<?php
namespace PHPSafeMode\RunTime\Resource;

use PHPSafeMode\RunTime\RunTime;
use PHPSafeMode\RunTime\RunTimeException;

class BaseResource {
	protected $runTime;
	
	public function __construct(RunTime $runTime) {
		$this->runTime = $runTime;
	}
	
	/**
	 * @return RunTime
	 */
	protected function runTime() {
		return $this->runTime;
	}
	
	protected function generateCodeFromFile($file, $ext = 'php') {
		$base = __DIR__ . '/code';
		$path = $base . '/' . $file . '.' . $ext;
		if (!file_exists($path)) throw new RunTimeException('generate code from file failed: '. $file);
		
		$code = file_get_contents($path);
		if (strpos($code, '<?php') !== false) $code = substr($code, 5);
		
		return $code;
	}
	
	protected function generateFunctionFromVar($name, $variable) {
		$variable = var_export($variable, true);
		
		return '
function ' . $name . '() {
	return ' . $variable . ';
}
';
	}
}