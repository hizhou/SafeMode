<?php
namespace PHPSafeMode\Tests\RunTime\Resource;

use PHPSafeMode\Tests\BaseTestCase;

class StorageTest extends BaseTestCase {
	public function testSetSafePath_CreateDir() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->storage()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow

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
		$mode->runTime()->storage()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow
		
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
		$mode->runTime()->storage()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow

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

	public function testSetSafePath_ReadFile() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->storage()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow

		$codeSpecify = 'storage/read_file_outside_absolute';
		$this->assertContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'storage/read_file_outside_relative';
		$this->assertContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'storage/read_file_inside_absolute';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'storage/read_file_inside_relative';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetSafePath_WriteFile() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->storage()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow
	
		$codeSpecify = 'storage/write_file_outside_absolute';
		$this->assertContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
	
		$codeSpecify = 'storage/write_file_outside_relative';
		$this->assertContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
	
		$codeSpecify = 'storage/write_file_inside_absolute';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
	
		$codeSpecify = 'storage/write_file_inside_relative';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetSafePath_RmFile() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->storage()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow
	
		$codeSpecify = 'storage/rm_file_outside_absolute';
		$this->assertContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
	
		$codeSpecify = 'storage/rm_file_outside_relative';
		$this->assertContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));


		$codeSpecify = 'storage/write_file_inside_absolute';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
		$codeSpecify = 'storage/write_file_inside_relative';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'storage/write_file_inside_relative';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
		$codeSpecify = 'storage/rm_file_inside_relative';
		$this->assertNotContains('path not safe', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetAllowedTypes() {
		$allowed = array('txt', 'md');
		
		$mode = $this->getNewSafeMode();
		$mode->runTime()->storage()->setAllowedTypes($allowed);
		
		$codeSpecify = 'storage/types_allow_read_and_write';
		$this->assertNotContains('file type not allowed', $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'storage/types_deny_read';
		$this->assertContains('file type not allowed', $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'storage/types_deny_write';
		$this->assertContains('file type not allowed', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetWriteMaxSize() {
		$maxMegaByte = 1;
		
		$mode = $this->getNewSafeMode();
		$mode->runTime()->storage()->setWriteMaxSize($maxMegaByte);
		
		$codeSpecify = 'storage/write_greater_then_max_size';
		$this->assertContains('beyond max file size', $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'storage/write_less_then_max_size';
		$this->assertNotContains('beyond max file size', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetWriteMaxFiles() {
		$maxFiles = 5;
		
		$mode = $this->getNewSafeMode();
		$mode->runTime()->storage()->setWriteMaxFiles($maxFiles);

		$codeSpecify = 'storage/create_file_or_dir_greater_then_max';
		$this->assertContains('beyond max files', $this->runInSafeMode($mode, $codeSpecify));
		
		$this->envProvider()->clearSafePath();
		
		$codeSpecify = 'storage/create_file_or_dir_less_then_max';
		$this->assertNotContains('beyond max files', $this->runInSafeMode($mode, $codeSpecify));
	}
}

