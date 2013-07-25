<?php 
namespace PHPSafeMode\RunTime\Resource;

use PHPSafeMode\RunTime\RunTimeException;
use PHPSafeMode\Generator\Type\ExpressionGenerator;

class Memory extends BaseResource {
	/**
	 * @param int $limit MegaBytes
	 */
	public function setMemoryLimit($limit) {
		if ($limit <= 0)  throw new RunTimeException("set memory limit must > 0");
		
		$this->runTime()->generatorContainer()->add(
			new ExpressionGenerator('expr_set_memory_limit', 'ini_set("memory_limit", "' . $limit . 'M");')
		);
	}
}