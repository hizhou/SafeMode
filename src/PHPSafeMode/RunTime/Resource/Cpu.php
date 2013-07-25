<?php 
namespace PHPSafeMode\RunTime\Resource;

use PHPSafeMode\RunTime\RunTimeException;
use PHPSafeMode\Generator\Type\ExpressionGenerator;

class Cpu extends BaseResource {
	public function setTimeLimit($seconds) {
		if ($seconds <= 0) throw new RunTimeException("set time limit must > 0");
		
		$this->runTime()->generatorContainer()->add(
			new ExpressionGenerator('expr_set_time_limit', 'set_time_limit(' . $seconds . ');')
		);
	}
}