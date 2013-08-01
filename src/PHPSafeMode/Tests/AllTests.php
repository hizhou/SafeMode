<?php
namespace PHPSafeMode\Tests;

class AllTests extends \PHPUnit_Framework_TestSuite {
	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('PHPSafeMode_AllTests');
		$suite->addTestSuite(__NAMESPACE__ . '\RunTime\AllRunTimeTests');
		$suite->addTestSuite(__NAMESPACE__ . '\Setting\AllSettingTests');
		return $suite;
	}
}