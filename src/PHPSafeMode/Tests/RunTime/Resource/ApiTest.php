<?php
namespace PHPSafeMode\Tests\RunTime\Resource;

use PHPSafeMode\Tests\BaseTestCase;

class ApiTest extends BaseTestCase {
	public function testDisableFunctionsObvious() {
		$disabled = 'exec';
		
		$mode = $this->getNewSafeMode();
		$mode->runTime()->api()->disableFunctions('exec');
		
		$file = $mode->generateSafeCode($this->getCode('api/exec_obvious'), 'index.php', 'bootstrap.php');
			
		$result = $this->scriptRunner()->run($file);
		
		//echo $result;
		$this->assertContains("Fatal error: Uncaught exception 'Exception' with message 'function disabled: $disabled'", $result);
	}
	
	public function testDisableFunctionsVar() {
		$disabled = 'exec';
		
		$mode = $this->getNewSafeMode();
		$mode->runTime()->api()->disableFunctions('exec');
		
		$file = $mode->generateSafeCode($this->getCode('api/exec_var'), 'index.php', 'bootstrap.php');
			
		$result = $this->scriptRunner()->run($file);
			
		$this->assertContains("Fatal error: Uncaught exception 'Exception' with message 'function disabled: $disabled'", $result);
	}
}
