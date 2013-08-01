<?php
namespace PHPSafeMode\Tests\Setting;

class AllSettingTests extends \PHPUnit_Framework_TestSuite {
	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('PHPSafeMode_Setting');
		$suite->addTestSuite(__NAMESPACE__ . '\Impl\DefaultImplTest');
		return $suite;
	}
}