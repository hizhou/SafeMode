<?php
namespace PHPSafeMode\Tests\RunTime\Resource;

use PHPSafeMode\Tests\BaseTestCase;

use PHPSafeMode\Tool\ManualParser;

class StorageTest extends BaseTestCase {
	public function testSetSafePath_OutSide() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->code()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow

		$man = new ManualParser();
		
		exit;
	}
	
	public function testSetSafePath_InSide() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->code()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow
		
		
	}
}