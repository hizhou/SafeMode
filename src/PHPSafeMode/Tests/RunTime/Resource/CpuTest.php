<?php
namespace PHPSafeMode\Tests\RunTime\Resource;

use PHPSafeMode\Tests\BaseTestCase;
use PHPSafeMode\SafeMode;

class CpuTest extends BaseTestCase {
	public function testSetTimeLimit() {
		$mode = $this->getSafeMode();
		$mode->runTime()->cpu()->setTimeLimit(2);
		$mode->generateSafeCode($this->getCode(), 'index.php', $this->getProvider()->getBootstrapPath() . 'b.php');
	}
	
	protected function getSafeMode() {
		return new SafeMode($this->getProvider()->getSafePath());
	}
	
	private function getCode() {
		return '<?php while (1) { echo 111; }';
	}
}

/*
class ParserTest extends PHPSafeModeTestCase {
	public function _testConvertMethodCallName() {
		$code = '<?php class A {
			public function aaa($a, $b) {
				echo $a . ":" . $b;
			}
		}';
		$code .= '$a = new A();
		$b = "xxx";
		$a->aaa($b, "bbb");';
		
		$this->getParser()->outputCode($this->getParser()->convertMethodCall($code));
	}
	
	public function _testConvertMethodCallVar() {
		$code = '<?php class A {
			public function aaa($a, $b) {
				echo $a . ":" . $b;
			}
		}';
		$code .= '$a = new A();
		$b = "xxx";
		$method = "aaa";
		$a->$method($b, "bbb");';
		
		$this->getParser()->outputCode($this->getParser()->convertMethodCall($code));
	}
	
	public function _testConvertMethodCallThis() {
		$code = '<?php class A {
			public function aaa($a, $b) {
				echo $a . ":" . $b;
			}
			public function bbb() {
				$this->aaa("111", "222");
			}
		}';
		$code .= '$a = new A();
		$b = "xxx";
		$method = "bbb";
		$a->$method();';
		
		$this->getParser()->outputCode($this->getParser()->convertMethodCall($code));
	}
	
	public function _testConvertMethodCallStatic() {
		$code = '<?php class A {
			public static function aaa($a, $b) {
				echo $a . ":" . $b;
			}
		}';
		$code .= '$b = "bbb";
		A::aaa("aaa", $b);';
		
		$this->getParser()->outputCode($this->getParser()->convertMethodCall($code));
	}
	
	public function testConvert() {
		$code = '<?php class A {
			public $name = "xxxxxx";
			private $secret = "un tell able";
		}';
		$code .= '$a = new A();
		$a->name;';
		
		var_dump($this->getParser()->convert($code));
	}
	
	
	
	private function getParser() {
		return new Parser();
	}
}
 * */