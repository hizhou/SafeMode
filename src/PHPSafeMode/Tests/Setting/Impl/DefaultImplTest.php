<?php
namespace PHPSafeMode\Tests\Setting\Impl;

use PHPSafeMode\Tests\BaseTestCase;

use PHPSafeMode\Setting\Impl\DefaultImpl;

class DefaultImplTest extends BaseTestCase {
	private $setting;

	public function testDisabledFunctions1() {
		$this->__testDisabledFunctions(1, 20);
	}
	
	public function testDisabledFunctions2() {
		$this->__testDisabledFunctions(2, 20);
	}
	
	public function testDisabledFunctions3() {
		$this->__testDisabledFunctions(3, 20);
	}
	
	public function testDisabledFunctions4() {
		$this->__testDisabledFunctions(4, 20);
	}
	
	public function testDisabledFunctions5() {
		$this->__testDisabledFunctions(5, 20);
	}
	
	public function testDisabledFunctions6() {
		$this->__testDisabledFunctions(6, 20);
	}
	
	public function testDisabledFunctions7() {
		$this->__testDisabledFunctions(7, 20);
	}
	
	public function testDisabledFunctions8() {
		$this->__testDisabledFunctions(8, 20);
	}
	
	public function testDisabledFunctions9() {
		$this->__testDisabledFunctions(9, 20);
	}
	
	public function testDisabledFunctions10() {
		$this->__testDisabledFunctions(10, 20);
	}
	
	public function testDisabledFunctions11() {
		$this->__testDisabledFunctions(11, 20);
	}
	
	public function testDisabledFunctions12() {
		$this->__testDisabledFunctions(12, 20);
	}
	
	public function testDisabledFunctions13() {
		$this->__testDisabledFunctions(13, 20);
	}
	
	public function testDisabledFunctions14() {
		$this->__testDisabledFunctions(14, 20);
	}
	
	public function testDisabledFunctions15() {
		$this->__testDisabledFunctions(15, 20);
	}
	
	public function testDisabledFunctions16() {
		$this->__testDisabledFunctions(16, 20);
	}
	
	public function testDisabledFunctions17() {
		$this->__testDisabledFunctions(17, 20);
	}
	
	public function testDisabledFunctions18() {
		$this->__testDisabledFunctions(18, 20);
	}
	
	public function testDisabledFunctions19() {
		$this->__testDisabledFunctions(19, 20);
	}
	
	public function testDisabledFunctions20() {
		$this->__testDisabledFunctions(20, 20);
	}
	
	private function __testDisabledFunctions($step, $steps) {
		$mode = $this->getDefaultSettingSafeMode();
		
		$disables = $this->getSetting()->getDisabledFunctions();
		$total = count($disables);
		
		if ($step < 1) $step = 1;
		if ($step > $steps) $step = $steps;
		$average = ceil($total / $steps);
		$offset = ($step - 1) * $average;
		
		foreach (array_slice($disables, $offset, $average) as $name) {
			$code = '<?php ' . $name . '();';
			if ($name == 'eval') $code = '<?php ' . $name . '("");';

			if (function_exists($name)) {
				$this->assertContains('函数 ' . $name . ' 被禁用', $this->runInSafeMode($mode, $code));
			} else {
				$this->assertContains('函数 ' . $name . ' 不存在', $this->runInSafeMode($mode, $code));
			}
		}
	}

	
	
	public function testDisabledClassesNew() {
		$mode = $this->getDefaultSettingSafeMode();
		foreach ($this->getSetting()->getDisabledClasses() as $name) {
			$code = '<?php $a = new ' . $name . ';';
			$this->assertContains('类 ' . $name . ' 被禁用', $this->runInSafeMode($mode, $code));
		}
	}

	public function testDisabledClassesStaticCall() {
		$mode = $this->getDefaultSettingSafeMode();
		foreach ($this->getSetting()->getDisabledClasses() as $name) {
			$code = '<?php $a = ' . $name . '::a();';
			$this->assertContains('类 ' . $name . ' 被禁用', $this->runInSafeMode($mode, $code));
		}
	}

	public function testDisabledClassesExtend() {
		$mode = $this->getDefaultSettingSafeMode();
		$finalClasses = array('mysqli_driver', 'mysqli_warning', 'closure', 'tidynode', 'pdorow', 'sqliteresult', 'sqliteunbuffered', 'sqliteexception');
		
		foreach ($this->getSetting()->getDisabledClasses() as $name) {
			$code = '<?php class OSDJFxcv extends ' . $name . ' {}';
			if (class_exists($name)) {
				if (!in_array($name, $finalClasses))
					$this->assertContains('类 ' . $name . ' 被禁用', $this->runInSafeMode($mode, $code));
			} else {
				$this->runInSafeMode($mode, $code);
			}
		}
	}
	
	public function testUnsafeFileSystemFunctions() {
		$mode = $this->getDefaultSettingSafeMode();

		$errorMsg = '目录不存在，或在访问范围之外';
		
		foreach ($this->getSetting()->getUnsafeFileSystemFunctions() as $type => $functions) {
			foreach ($functions as $name => $config) {
				if (!function_exists($name)) {continue;}
				
				if ($type == 'readDir') {
					$code = '<?php ' . $name . '(' . $this->genereateParamString($config['readDir'], '/tmp') . ');';
					$this->assertContains($errorMsg, $this->runInSafeMode($mode, $code));
				} elseif ($type == 'readFile') {
					if (isset($config['writeFile'])) {continue;}
					
					$code = '<?php ' . $name . '(' . $this->genereateParamString($config['readFile'], '/tmp/somefile.txt') . ');';
					$this->assertContains($errorMsg, $this->runInSafeMode($mode, $code));
				} elseif ($type == 'writeFile') {
					if ($config['writeFile'] < 0) {continue;}
					
					$code = '<?php ' . $name . '(' . $this->genereateParamString($config['writeFile'], '/tmp/somefile.txt') . ');';
					$this->assertContains($errorMsg, $this->runInSafeMode($mode, $code));
				} elseif ($type == 'createDir') {
					$code = '<?php ' . $name . '(' . $this->genereateParamString($config['createDir'], '/tmp/somedir') . ');';
					$this->assertContains('上级' . $errorMsg, $this->runInSafeMode($mode, $code));
				} elseif ($type == 'rmDir') {
					$code = '<?php ' . $name . '(' . $this->genereateParamString($config['rmDir'], '/tmp/somedir') . ');';
					$this->assertContains($errorMsg, $this->runInSafeMode($mode, $code));
				} elseif ($type == 'rmFile') {
					$code = '<?php ' . $name . '(' . $this->genereateParamString($config['rmFile'], '/tmp/somefile.txt') . ');';
					$this->assertContains($errorMsg, $this->runInSafeMode($mode, $code));
				}
			}
		}
	}
	
	private function genereateParamString($pos, $value) {
		if ($pos == 0) return "'$value'";
		
		$str = '';
		for ($i = 0; $i < $pos; $i++) {
			$str .= "'', ";
		}
		return $str . "'$value'";
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