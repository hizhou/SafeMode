<?php 
namespace PHPSafeMode;

use PHPSafeMode\RunTime\RunTime;

class SafeMode {
	private $runTime;
	
	private $safePath;
	private $bootstrapPath;
	
	public function __construct($safePath, $bootstrapPath) {
		$this->safePath = $safePath;
		$this->bootstrapPath = $bootstrapPath;
		
		$this->runTime = new RunTime($safePath);
	}
	
	public function generateSafeCode($code, $saveTo, $bootstrapSaveTo) {
		return $this->runTime()->generatorContainer()->make($code, $this->safePath . '/' . $saveTo,  $this->bootstrapPath . '/' . $bootstrapSaveTo);
	}
	
	
	
	public function runTime() {
		return $this->runTime;
	}
}
