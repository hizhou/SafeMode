<?php
namespace PHPSafeMode\Tests\RunTime\Resource;

use PHPSafeMode\Tests\BaseTestCase;

class CodeTest extends BaseTestCase {
	public function testSetSafePath_IncludeOutSide() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->code()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow
		
		$errorMsg = '不存在，或在访问范围之外';

		$codeSpecify = 'code/file_for_include';
		$this->runInOriginalMode($codeSpecify, false, 'forinclude.php');
		
		$codeSpecify = 'code/include_outside_absolute';
		$this->assertNotContains($errorMsg, $this->runInOriginalMode($codeSpecify));
		$this->assertContains($errorMsg . '  ', $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'code/include_outside_notexist';
		$this->assertNotContains($errorMsg, $this->runInOriginalMode($codeSpecify));
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'code/include_outside_exist';
		$this->assertNotContains($errorMsg, $this->runInOriginalMode($codeSpecify));
		$this->assertContains($errorMsg . '  ', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetSafePath_IncludeInSide() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->code()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow
		
		$errorMsg = '不存在，或在访问范围之外';
		
		$codeSpecify = 'code/file_for_include';
		$this->runInOriginalMode($codeSpecify, false, 'forinclude.php');
		$this->runInSafeMode($mode, $codeSpecify, false, 'forinclude.php');
		
		$codeSpecify = 'code/include_inside_notexist';
		$this->assertNotContains($errorMsg, $this->runInOriginalMode($codeSpecify));
		$this->assertContains($errorMsg, $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'code/include_inside_exist';
		$this->assertContains('got included', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('got included', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetIncludeTypes_Allowed() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->code()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow
		$mode->runTime()->code()->setIncludeTypes('php');
		
		$codeSpecify = 'code/file_for_include';
		$this->runInOriginalMode($codeSpecify, false, 'forinclude.php');
		$this->runInSafeMode($mode, $codeSpecify, false, 'forinclude.php');
		
		$codeSpecify = 'code/include_inside_exist';
		$this->assertContains('got included', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('got included', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetIncludeTypes_Denied() {
		$allowed = array('inc', 'html');

		$mode = $this->getNewSafeMode();
		$mode->runTime()->code()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow
		$mode->runTime()->code()->setIncludeTypes($allowed);
		
		$codeSpecify = 'code/file_for_include';
		$this->runInOriginalMode($codeSpecify, false, 'forinclude.php');
		$this->runInSafeMode($mode, $codeSpecify, false, 'forinclude.php');
		
		$codeSpecify = 'code/include_inside_exist';
		$this->assertContains('got included', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('被加载文件类型只允许', $this->runInSafeMode($mode, $codeSpecify));
	}
}
