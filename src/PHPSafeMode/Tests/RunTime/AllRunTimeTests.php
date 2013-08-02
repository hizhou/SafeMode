<?php
namespace PHPSafeMode\Tests\RunTime;

class AllRunTimeTests extends \PHPUnit_Framework_TestSuite {
	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('PHPSafeMode_RunTime');
		$suite->addTestSuite(__NAMESPACE__ . '\Resource\EnvTest');
		$suite->addTestSuite(__NAMESPACE__ . '\Resource\CpuTest');
		$suite->addTestSuite(__NAMESPACE__ . '\Resource\MemoryTest');
		$suite->addTestSuite(__NAMESPACE__ . '\Resource\ApiTest');
		$suite->addTestSuite(__NAMESPACE__ . '\Resource\CodeTest');
		$suite->addTestSuite(__NAMESPACE__ . '\Resource\StorageTest');
		return $suite;
	}
}