<?php 
namespace PHPSafeMode\RunTime\Resource;

use PHPSafeMode\RunTime\RunTimeException;

class Cpu extends ResourceBase {
	public function setTimeLimit($seconds) {
		if ($seconds <= 0) throw new RunTimeException("set time limit must > 0");
		
		$this->bootstrapCodes[] = 'set_time_limit(' . $seconds . ');';
	}
}