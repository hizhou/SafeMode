<?php
namespace PHPSafeMode\Tests\Generator;

use PHPSafeMode\Tests\BaseTestCase;

class GeneratorContainerTest extends BaseTestCase {
	public function testPrependCodeToNamespace() {
		$mode = $this->getNewSafeMode();
		
		$codeSpecify = 'generator/namespace_expr';
		$this->assertNotContains('error', $this->runInOriginalMode($codeSpecify));
		$this->assertNotContains('error', $this->runInSafeMode($mode, $codeSpecify));
		
	}
}