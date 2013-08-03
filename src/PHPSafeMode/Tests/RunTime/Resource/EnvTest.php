<?php
namespace PHPSafeMode\Tests\RunTime\Resource;

use PHPSafeMode\Tests\BaseTestCase;

class EnvTest extends BaseTestCase {
	public function testSetDisplayErrors() {
		$defaultIsDisplay = (bool) ini_get('display_errors');
		
		$specifies = $this->codeProvider()->findSpecifies('env/trigger_*');
		foreach ($specifies as $codeSpecify) {
			if ($defaultIsDisplay) {
				$this->assertContains(" on line ", $this->runInOriginalMode($codeSpecify));
			}
			
			$mode = $this->getNewSafeMode();
			$mode->runTime()->env()->setDisplayErrors(false);
			$this->assertNotContains(" on line ", $this->runInSafeMode($mode, $codeSpecify));
		}
	}
	
	public function testSetErrorReporting() {
		$codeSpecify = 'env/trigger_all_levels';
		
		$defaultSetting = error_reporting();
		$errorTitles = array(E_ERROR => 'Fatal error:', E_WARNING => 'Warning:', E_NOTICE => 'Notice:');
		
		$orginalResult = $this->runInOriginalMode($codeSpecify);
		foreach ($errorTitles as $level => $title) {
			if ($defaultSetting & $level) {
				$this->assertContains($title, $orginalResult);
			}
		}
		
		$newSetting = 0;
		$mode = $this->getNewSafeMode();
		$mode->runTime()->env()->setErrorReporting($newSetting);
		$this->assertNotContains(" on line ", $this->runInSafeMode($mode, $codeSpecify));
		
		foreach ($errorTitles as $level => $title) {
			$newSetting = $newSetting | $level;
			$mode = $this->getNewSafeMode();
			$mode->runTime()->env()->setErrorReporting($newSetting);
			$this->assertContains($title, $this->runInSafeMode($mode, $codeSpecify));
		}
	}
	
	public function testSetErrorHandler_E_PARSE() {
		$mode = $this->getHandleErrorSafeMode();
		
		$codeSpecify = '<?php sjd fo;';
		
		$this->assertContains('Parse error:', $this->runInOriginalMode($codeSpecify));
		
		try {
			$e = null;
			$this->runInSafeMode($mode, $codeSpecify);
		} catch (\Exception $e) {}
		
		$this->assertNotNull($e);
		$this->assertInstanceOf('PHPParser_Error', $e);
	}
	
	public function testSetErrorHandler_E_ERROR() {
		$mode = $this->getHandleErrorSafeMode();
		
		$codeSpecify = 'env/handle_e_error';
		
		$this->assertContains('Fatal error:', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('Fatal error:', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetErrorHandler_E_USER_ERROR() {
		$mode = $this->getHandleErrorSafeMode();
		
		$codeSpecify = 'env/handle_e_user_error';
		
		$this->assertContains('Fatal error:', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('Fatal error :', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetErrorHandler_E_WARNING() {
		$mode = $this->getHandleErrorSafeMode();
		
		$codeSpecify = 'env/handle_e_warning';
		
		$this->assertContains('Warning:', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('Warning :', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetErrorHandler_E_USER_WARNING() {
		$mode = $this->getHandleErrorSafeMode();
		
		$codeSpecify = 'env/handle_e_user_warning';
		
		$this->assertContains('Warning:', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('Warning :', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetErrorHandler_E_NOTICE_ALL() {
		$mode = $this->getHandleErrorSafeMode();
		
		$codeSpecify = 'env/handle_e_notice_all';
		
		$this->assertContains('Notice:', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('Notice :', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetErrorHandler_E_DEPRECATED_ALL() {
		$mode = $this->getHandleErrorSafeMode();
		
		$codeSpecify = 'env/handle_e_deprecated_all';
		
		$this->assertContains('Deprecated:', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('Deprecated :', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testSetErrorHandler_E_STRICT() {
		$mode = $this->getHandleErrorSafeMode();
		
		$codeSpecify = 'env/handle_e_strict';
		
		$this->assertContains('Strict Standards:', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('Warning :', $this->runInSafeMode($mode, $codeSpecify));
	}

	public function testSetSafePath() {
		$mode = $this->getHandleErrorSafeMode();
		
		$codeSpecify = 'env/handle_e_warning';
		
		$this->assertContains('tmp.php', $this->runInOriginalMode($codeSpecify));
		$this->assertNotContains('bootstrap.php', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	
	
	
	private function getHandleErrorSafeMode() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->env()->setDisplayErrors(true);
		$mode->runTime()->env()->setErrorReporting(E_ALL | E_STRICT);
		$mode->runTime()->env()->setErrorHandler();
		return $mode;
	}
}
