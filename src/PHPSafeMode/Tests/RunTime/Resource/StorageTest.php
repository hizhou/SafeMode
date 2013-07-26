<?php
namespace PHPSafeMode\Tests\RunTime\Resource;

use PHPSafeMode\Tests\BaseTestCase;

class StorageTest extends BaseTestCase {
	public function testSetSafePath_OutSide() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->code()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow

	}
	
	public function testSetSafePath_InSide() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->code()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow
		
		
	}
}