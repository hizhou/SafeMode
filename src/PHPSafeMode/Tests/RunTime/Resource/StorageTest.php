<?php
namespace PHPSafeMode\Tests\RunTime\Resource;

use PHPSafeMode\Tests\BaseTestCase;

class StorageTest extends BaseTestCase {
	public function testSetSafePath_CreateDir() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->storage()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow

		$errorMsg = '上级目录不存在，或在访问范围之外';
		
		$codeSpecify = 'storage/create_dir_outside_absolute';
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'storage/create_dir_outside_relative';
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'storage/create_dir_inside_absolute';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'storage/create_dir_inside_relative';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'storage/create_dir_inside_jump';
		$this->assertContains('禁止递归创建目录', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetSafePath_RmDir() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->storage()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow
		
		$errorMsg = '目录不存在，或在访问范围之外';
		
		$codeSpecify = 'storage/rm_dir_outside_absolute';
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'storage/rm_dir_outside_relative';
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));

		
		$codeSpecify = 'storage/create_dir_inside_absolute';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
		$codeSpecify = 'storage/rm_dir_inside_absolute';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));


		$codeSpecify = 'storage/create_dir_inside_relative';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
		$codeSpecify = 'storage/rm_dir_inside_relative';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
	}

	public function testSetSafePath_ReadDir() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->storage()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow

		$errorMsg = '目录不存在，或在访问范围之外';
		
		$codeSpecify = 'storage/read_dir_outside_absolute';
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'storage/read_dir_outside_relative';
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));

		
		$codeSpecify = 'storage/create_dir_inside_absolute';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
		$codeSpecify = 'storage/read_dir_inside_absolute';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));


		$codeSpecify = 'storage/create_dir_inside_relative';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
		$codeSpecify = 'storage/read_dir_inside_relative';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
	}

	public function testSetSafePath_ReadFile() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->storage()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow

		$errorMsg = '目录不存在，或在访问范围之外';
		
		$codeSpecify = 'storage/read_file_outside_absolute';
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'storage/read_file_outside_relative';
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'storage/read_file_inside_absolute';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'storage/read_file_inside_relative';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetSafePath_WriteFile() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->storage()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow

		$errorMsg = '目录不存在，或在访问范围之外';
		
		$codeSpecify = 'storage/write_file_outside_absolute';
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
	
		$codeSpecify = 'storage/write_file_outside_relative';
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
	
		$codeSpecify = 'storage/write_file_inside_absolute';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
	
		$codeSpecify = 'storage/write_file_inside_relative';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetSafePath_RmFile() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->storage()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow
	
		$errorMsg = '目录不存在，或在访问范围之外';
		
		$codeSpecify = 'storage/rm_file_outside_absolute';
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
	
		$codeSpecify = 'storage/rm_file_outside_relative';
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));


		$codeSpecify = 'storage/write_file_inside_absolute';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
		$codeSpecify = 'storage/write_file_inside_relative';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'storage/write_file_inside_relative';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
		$codeSpecify = 'storage/rm_file_inside_relative';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetAllowedTypes() {
		$allowed = array('txt', 'md');
		
		$errorMsg = '操作的文件类型只允许';
		
		$mode = $this->getNewSafeMode();
		$mode->runTime()->storage()->setAllowedTypes($allowed);
		
		$codeSpecify = 'storage/types_allow_read_and_write';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'storage/types_deny_read';
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'storage/types_deny_write';
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetWriteMaxSize() {
		$maxMegaByte = 1;
		
		$errorMsg = '文件大小不能超过';
		
		$mode = $this->getNewSafeMode();
		$mode->runTime()->storage()->setWriteMaxSize($maxMegaByte);
		
		$codeSpecify = 'storage/write_greater_then_max_size';
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'storage/write_less_then_max_size';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetWriteMaxFiles() {
		$maxFiles = 5;
		
		$errorMsg = '文件和文件夹总数不能超过';
		
		$mode = $this->getNewSafeMode();
		$mode->runTime()->storage()->setWriteMaxFiles($maxFiles);

		$codeSpecify = 'storage/create_file_or_dir_greater_then_max';
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
		
		$this->envProvider()->clearSafePath();
		
		$codeSpecify = 'storage/create_file_or_dir_less_then_max';
		$this->assertNotContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
	}
}

