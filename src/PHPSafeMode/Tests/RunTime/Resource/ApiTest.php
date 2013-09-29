<?php
namespace PHPSafeMode\Tests\RunTime\Resource;

use PHPSafeMode\Tests\BaseTestCase;

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
					'函数 ' . $disabled . ' 被禁用',
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

			$result = $this->runInSafeMode($mode, $codeSpecify);
			$theOne = strpos($result, ' strtoupper ') ? 'strtoupper' : 'substr';
			$this->assertContains(
				'函数 ' . $theOne . ' 被禁用',
				$result
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
		$className = 'test';
		$mode = $this->getNewSafeMode();
		$mode->runTime()->api()->disableClasses($className);

		$codeSpecify = 'api/use_class_with_new';
		$this->assertContains('类 ' . $className . ' 被禁用', $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'api/use_class_with_new_var';
		$this->assertContains('类 ' . $className . ' 被禁用', $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'api/use_class_with_extend';
		$this->assertContains('类 ' . $className . ' 被禁用', $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'api/use_class_with_static_call';
		$this->assertContains('类 ' . $className . ' 被禁用', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testDisableClassesByRename() {
		$className = 'test';
		$mode = $this->getNewSafeMode();
		$mode->runTime()->api()->disableClasses($className);

		$codeSpecify = 'api/use_class_with_new_rename';
		$this->assertNotContains('被禁用', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('类 ' . $className . ' 被禁用', $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'api/use_class_with_extend_rename';
		$this->assertNotContains('被禁用', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('类 ' . $className . ' 被禁用', $this->runInSafeMode($mode, $codeSpecify));
		
		$codeSpecify = 'api/use_class_with_static_call_rename';
		$this->assertNotContains('被禁用', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('类 ' . $className . ' 被禁用', $this->runInSafeMode($mode, $codeSpecify));
		
	}

	public function testDisableClassInNamespace() {
		$className = 'test\forinclude\aaa';
		$mode = $this->getNewSafeMode();
		$mode->runTime()->api()->disableClasses($className);

		$codeSpecify = 'api/for_namespace_include';
		$this->assertNotContains('error', $this->runInOriginalMode($codeSpecify, false, 'forinclude.php'));
		$this->assertNotContains('error', $this->runInSafeMode($mode, $codeSpecify, false, 'forinclude.php'));

		$codeSpecify = 'api/use_namespace_class_with_new';
		$this->assertNotContains('error', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('类 ' . $className . ' 被禁用', $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'api/use_namespace_class_with_new_var';
		$this->assertNotContains('error', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('类 ' . $className . ' 被禁用', $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'api/use_namespace_class_with_static_call';
		$this->assertNotContains('error', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('类 ' . $className . ' 被禁用', $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'api/use_namespace_class_with_extend';
		$this->assertNotContains('error', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('类 ' . $className . ' 被禁用', $this->runInSafeMode($mode, $codeSpecify));
	}

	public function testDisableFunctionInNamespace() {
		$functionName = 'test\forinclude\bbb';
		$mode = $this->getNewSafeMode();
		$mode->runTime()->api()->disableFunctions($functionName);

		$codeSpecify = 'api/for_namespace_include';
		$this->assertNotContains('error', $this->runInOriginalMode($codeSpecify, false, 'forinclude.php'));
		$this->assertNotContains('error', $this->runInSafeMode($mode, $codeSpecify, false, 'forinclude.php'));

		$codeSpecify = 'api/function_namespace_call_general';
		$this->assertNotContains('error', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('函数 ' . $functionName . ' 被禁用', $this->runInSafeMode($mode, $codeSpecify));

		$codeSpecify = 'api/function_namespace_call_var';
		$this->assertNotContains('error', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('函数 ' . $functionName . ' 被禁用', $this->runInSafeMode($mode, $codeSpecify));
	}

	public function testDisableFunctionCallNamespaceSamename() {
		$functionName = 'var_dump';
		$mode = $this->getNewSafeMode();
		$mode->runTime()->api()->disableFunctions($functionName);

		$codeSpecify = 'api/function_namespace_call_samename';
		$this->assertContains('int(', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('函数 ' . $functionName . ' 被禁用', $this->runInSafeMode($mode, $codeSpecify));
	}

	public function testDisableClassNamespaceSamename() {
		$className = 'datetime';
		$mode = $this->getNewSafeMode();
		$mode->runTime()->api()->disableClasses($className);

		$codeSpecify = 'api/use_namespace_class_samename';
		$this->assertContains('["timezone"]', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('类 ' . $className . ' 被禁用', $this->runInSafeMode($mode, $codeSpecify));
	}

	public function testDisableFunctionCallInMultiNamespace() {
		$functionName = 'var_dump';
		$mode = $this->getNewSafeMode();
		$mode->runTime()->api()->disableFunctions($functionName);
	
		$codeSpecify = 'api/function_namespace_call_multinamespace';
		$this->assertContains('int(', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('函数 ' . $functionName . ' 被禁用', $this->runInSafeMode($mode, $codeSpecify));
	}
	
	public function testDisableClassInMultiNamespace() {
		$className = 'datetime';
		$mode = $this->getNewSafeMode();
		$mode->runTime()->api()->disableClasses($className);
	
		$codeSpecify = 'api/use_namespace_class_multinamespace';
		$this->assertContains('["timezone"]', $this->runInOriginalMode($codeSpecify));
		$this->assertContains('类 ' . $className . ' 被禁用', $this->runInSafeMode($mode, $codeSpecify));
	}
}
