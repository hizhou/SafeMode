<?php
namespace PHPSafeMode\Tests\RunTime\Resource;

use PHPSafeMode\Tests\BaseTestCase;

class MemoryTest extends BaseTestCase {
	public function testSetMemoryLimit() {
		$limit = 1;
		$codeSpecify = 'memory/use_' . $limit . '_m';
		
		$this->assertNotContains("Fatal error:", $this->runInOriginalMode($codeSpecify));
		
		$mode = $this->getNewSafeMode();
		$mode->runTime()->memory()->setMemoryLimit($limit);
		
		$this->assertContains(
			"Fatal error: Allowed memory size of", 
			$this->runInSafeMode($mode, $codeSpecify)
		);
	}
}