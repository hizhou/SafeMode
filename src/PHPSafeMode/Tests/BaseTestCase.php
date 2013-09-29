<?php 
namespace PHPSafeMode\Tests;

use PHPSafeMode\SafeMode;

abstract class BaseTestCase extends \PHPUnit_Framework_TestCase {
	protected $envProvider;
	protected $codeProvider;
	protected $scriptRunner;
	
	protected function setUp() {
		$this->envProvider()->clearSafePath();
		$this->envProvider()->clearBootstrapPath();
		$this->envProvider()->clearOriginPath();
	}
	protected function tearDown() {
	}
	
	

	protected function runInSafeMode(SafeMode $mode, $codeSpecify, $isDebug = false, $saveTo = 'index.php', $bootstrapSaveTo = 'bootstrap.php') {
		$code = $this->isSpecifyActuallyCode($codeSpecify) 
			? $codeSpecify : $this->codeProvider()->getCode($codeSpecify);
		$file = $mode->generateSafeCode($code, $saveTo, $bootstrapSaveTo);
		return $this->scriptRunner()->run($file, $isDebug);
	}
	
	protected function runInOriginalMode($codeSpecify, $isDebug = false, $saveTo = 'tmp.php') {
		$code = $this->isSpecifyActuallyCode($codeSpecify) 
			? $codeSpecify : $this->codeProvider()->getCode($codeSpecify);
		return $this->scriptRunner()->runCode($code, $saveTo, $isDebug);
	}
	


	protected function getNewSafeMode() {
		return new SafeMode($this->envProvider()->getSafePath(), $this->envProvider()->getBootstrapPath());
	}
	
	protected function envProvider() {
		if (!$this->envProvider) {
			$this->envProvider = new EnvProvider();
		}
		return $this->envProvider;
	}
	
	protected function codeProvider() {
		if (!$this->codeProvider) {
			$this->codeProvider = new CodeProvider();
		}
		return $this->codeProvider;
	}
	
	protected function scriptRunner() {
		if (!$this->scriptRunner) {
			$this->scriptRunner = new ScriptRunner($this->envProvider()->getOriginPath());
		}
		return $this->scriptRunner;
	}
	
	
	private function isSpecifyActuallyCode($codeSpecify) {
		return strpos($codeSpecify, ' ') || strpos($codeSpecify, "\n");
	}
}
