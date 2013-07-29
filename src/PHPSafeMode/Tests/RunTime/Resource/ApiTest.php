<?php
namespace PHPSafeMode\Tests\RunTime\Resource;

use PHPSafeMode\Tests\BaseTestCase;
use PHPSafeMode\SafeMode;

class ApiTest extends BaseTestCase {

	public function testDisableFunction() {
		$disabledList = array('strtoupper', 'substr');

		$specifies = $this->codeProvider()->findSpecifies('api/function_call_*');
		foreach ($disabledList as $disabled) {
			foreach ($specifies as $codeSpecify) {
				$this->assertNotContains("Fatal error:", $this->runInOriginalMode($codeSpecify));
					
				$mode = $this->getNewSafeMode();
				$mode->runTime()->api()->disableFunctions($disabled);
				$this->assertContains(
					"Uncaught exception 'Exception' with message 'function disabled: $disabled'",
					$this->runInSafeMode($mode, $codeSpecify)
				);
			}
		}
	}
	
	public function testDisableFunctions() {
		$disabled = array('strtoupper', 'substr');
		
		$codes = $this->codeProvider()->findSpecifies('api/function_call_*');
		foreach ($codes as $codeSpecify) {
			$this->assertNotContains("Fatal error:", $this->runInOriginalMode($codeSpecify));
			
			$mode = $this->getNewSafeMode();
			$mode->runTime()->api()->disableFunctions($disabled);
			$this->assertContains(
				"Uncaught exception 'Exception' with message 'function disabled: ",
				$this->runInSafeMode($mode, $codeSpecify)
			);
		}
	}
	
	public function testReplaceFunctions() {
		$replaceCode = $this->codeProvider()->getCode('api/function_for_replace');
		$replaces = array(
			'strtoupper' => $replaceCode,
			'substr' => $replaceCode,
		);
		
		$codes = $this->codeProvider()->findSpecifies('api/function_call_*');
		foreach ($codes as $codeSpecify) {
			$this->assertNotContains("Fatal error:", $this->runInOriginalMode($codeSpecify));
			
			$mode = $this->getNewSafeMode();
			$mode->runTime()->api()->replaceFunctions($replaces);
			
			$this->assertContains("function replaced", 
				$this->runInSafeMode($mode, $codeSpecify)
			);
		}
	}

	public function testDisableClasses() {
		$mode = $this->getNewSafeMode();
		$mode->runTime()->api()->disableClasses('test');

		$codeSpecify = 'api/use_class_with_new';
		$this->assertContains('class disabled', $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'api/use_class_with_new_var';
		$this->assertContains('class disabled', $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'api/use_class_with_extend';
		$this->assertContains('class disabled', $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'api/use_class_with_static_call';
		$this->assertContains('class disabled', $this->runInSafeMode($mode, $codeSpecify));
	}
}
