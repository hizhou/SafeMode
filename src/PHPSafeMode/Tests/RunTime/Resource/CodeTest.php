<?php
namespace PHPSafeMode\Tests\RunTime\Resource;

use PHPSafeMode\Tests\BaseTestCase;

class CodeTest extends BaseTestCase {
	public function testSetSafePath_IncludeOutSide() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->code()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow
		
		$codeSpecify = 'code/file_for_include';
		$this->runInOriginalMode($codeSpecify, false, 'forinclude.php');
		//$this->runInSafeMode($mode, $codeSpecify, false, 'forinclude.php');
		
		$codeSpecify = 'code/include_outside_absolute';
		$this->assertNotContains('be relative', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('be relative', $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'code/include_outside_notexist';
		$this->assertNotContains('not exist', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('not exist', $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'code/include_outside_exist';
		$this->assertNotContains('out of dir', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('out of dir', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetSafePath_IncludeInSide() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->code()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow
		
		$codeSpecify = 'code/file_for_include';
		$this->runInOriginalMode($codeSpecify, false, 'forinclude.php');
		$this->runInSafeMode($mode, $codeSpecify, false, 'forinclude.php');
		
		$codeSpecify = 'code/include_inside_notexist';
		$this->assertNotContains('not exist', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('not exist', $this->runInSafeMode($mode, $codeSpecify));
		
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
		$mode = $this->getNewSafeMode();
		$mode->runTime()->code()->setSafePath($this->envProvider()->getSafePath()); //has set by default flow
		$mode->runTime()->code()->setIncludeTypes('inc');
		
		$codeSpecify = 'code/file_for_include';
		$this->runInOriginalMode($codeSpecify, false, 'forinclude.php');
		$this->runInSafeMode($mode, $codeSpecify, false, 'forinclude.php');
		
		$codeSpecify = 'code/include_inside_exist';
		$this->assertContains('got included', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('type no allowed', $this->runInSafeMode($mode, $codeSpecify));
	}
}
