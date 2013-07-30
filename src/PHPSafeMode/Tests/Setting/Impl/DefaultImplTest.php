<?php
namespace PHPSafeMode\Tests\Setting\Impl;

use PHPSafeMode\Tests\BaseTestCase;

use PHPSafeMode\Setting\Impl\DefaultImpl;

class DefaultImplTest extends BaseTestCase {
	private $setting;

	public function xtestDisabledFunctions() {
		$mode = $this->getDefaultSettingSafeMode();

		foreach ($this->getSetting()->getDisabledFunctions() as $name) {
			$code = '<?php ' . $name . '();';
			if ($name == 'eval') $code = '<?php ' . $name . '("");';

			if (function_exists($name)) {
				$this->assertContains('function disabled', $this->runInSafeMode($mode, $code));
			} else {
				//TODO
				$this->assertContains('function not exist', $this->runInSafeMode($mode, $code));
			}
		}
	}

	public function testDisabledClasses() {
		$mode = $this->getDefaultSettingSafeMode();
		foreach ($this->getSetting()->getDisabledClasses() as $name) {
			$code = '<?php $a = new ' . $name . ';';
			//$this->assertContains('class disabled', $this->runInSafeMode($mode, $code));

			$code = '<?php $a = ' . $name . '::a();';
			//$this->assertContains('class disabled', $this->runInSafeMode($mode, $code));

			$code = '<?php class OSDJFxcv extends ' . $name . ' {}';
			if (class_exists($name)) {
				if (!in_array($name, array('mysqli_driver', 'mysqli_warning', 'closure', 'tidynode', 'pdorow', 'sqliteresult', 'sqliteunbuffered', 'sqliteexception')))
					$this->assertContains('class disabled', $this->runInSafeMode($mode, $code, true));
			} else {
				$this->runInSafeMode($mode, $code, true);
			}
		}
	}

	private function getDefaultSettingSafeMode() {
		return $this->getSetting()->make($this->getNewSafeMode());
	}

	private function getSetting() {
		if (!$this->setting) {
			$this->setting = new DefaultImpl();
		}
		return $this->setting;
	}
}