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
	
	


	protected function getNewSafeMode() {
		return new SafeMode($this->envProvider()->getSafePath(), $this->envProvider()->getBootstrapPath());
	}
	
	protected function getCode($specify) {
		return $this->codeProvider()->getCode($specify);
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
			$this->scriptRunner = new ScriptRunner();
		}
		return $this->scriptRunner;
	}
}
