<?php
namespace PHPSafeMode\Tests\RunTime\Resource;

use PHPSafeMode\Tests\BaseTestCase;

class StorageTest extends BaseTestCase {
	public function testSetSafePath_CreateDir() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->code()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow

		$codeSpecify = 'storage/create_dir_outside_absolute';
		$this->assertContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'storage/create_dir_outside_relative';
		$this->assertContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'storage/create_dir_inside_absolute';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'storage/create_dir_inside_relative';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'storage/create_dir_inside_jump';
		$this->assertContains('parent dir not exist', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetSafePath_RmDir() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->code()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow
		
		$codeSpecify = 'storage/rm_dir_outside_absolute';
		$this->assertContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'storage/rm_dir_outside_relative';
		$this->assertContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));

		
		$codeSpecify = 'storage/create_dir_inside_absolute';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
		$codeSpecify = 'storage/rm_dir_inside_absolute';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));


		$codeSpecify = 'storage/create_dir_inside_relative';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
		$codeSpecify = 'storage/rm_dir_inside_relative';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
	}

	public function testSetSafePath_ReadDir() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->code()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow

		$codeSpecify = 'storage/read_dir_outside_absolute';
		$this->assertContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'storage/read_dir_outside_relative';
		$this->assertContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));

		
		$codeSpecify = 'storage/create_dir_inside_absolute';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
		$codeSpecify = 'storage/read_dir_inside_absolute';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));


		$codeSpecify = 'storage/create_dir_inside_relative';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
		$codeSpecify = 'storage/read_dir_inside_relative';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
	}
}