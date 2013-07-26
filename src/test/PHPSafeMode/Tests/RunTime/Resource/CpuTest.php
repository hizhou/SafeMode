<?php
namespace PHPSafeMode\Tests\RunTime\Resource;

use PHPSafeMode\Tests\BaseTestCase;

class CpuTest extends BaseTestCase {
	public function testSetTimeLimit() {
		$limit = 2;
		$codeSpecify = 'cpu/endless_loop';
		
		//$this->assertNotContains("Fatal error:", $this->runInOriginalMode($codeSpecify));
		
		$mode = $this->getNewSafeMode();
		$mode->runTime()->cpu()->setTimeLimit($limit);
		
		$this->assertContains(
			"Maximum execution time of $limit seconds", 
			$this->runInSafeMode($mode, $codeSpecify)
		);
	}
}
