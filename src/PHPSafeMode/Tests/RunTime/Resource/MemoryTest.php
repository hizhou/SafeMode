<?php
namespace PHPSafeMode\Tests\RunTime\Resource;

use PHPSafeMode\Tests\BaseTestCase;

class MemoryTest extends BaseTestCase {
	public function testSetMemoryLimit() {
		$limit = 2;
		
		$mode = $this->getNewSafeMode();
		$mode->runTime()->memory()->setMemoryLimit($limit);
		
		$file = $mode->generateSafeCode($this->getCode('memory/use_' . $limit . '_m'), 'index.php', 'bootstrap.php');
		
		$result = $this->scriptRunner()->run($file);
		$this->assertContains("Fatal error: Allowed memory size of", $result);
	}
}