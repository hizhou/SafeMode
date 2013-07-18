<?php 
namespace PHPSafeMode\RunTime\Resource;

use PHPSafeMode\RunTime\RunTimeException;

class Memory extends ResourceBase {
	/**
	 * @param int $limit MegaBytes
	 */
	public function setMemoryLimit($limit) {
		if ($limit <= 0)  throw new RunTimeException("set memory limit must > 0");
		
		$this->appendBootstrapCode('ini_set("memory_limit", "' . $limit . 'M");');
	}
}