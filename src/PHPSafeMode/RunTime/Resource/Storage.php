<?php 
namespace PHPSafeMode\RunTime\Resource;

use \PHPSafeMode\RunTime\RunTimeException;

class Storage extends ResourceBase {
	private $safePath;

	private $readTypes = array();
	private $writeTypes = array();
	private $writeMaxSize = 0;
	private $writeMaxFiles = 0;
	
	public function setSafePath($safePath) {
		$this->safePath = $safePath;
		if (!is_dir($this->safePath)) throw new RunTimeException('safe path not exist');
	}

	public function setReadTypes($types) {
		if (!is_array($types)) $types = array($types);
		
		foreach ($types as $type) {
			$type = strtolower($type);
			$this->readTypes[$type] = $type;
		}
	}

	public function setWriteTypes($types) {
		if (!is_array($types)) $types = array($types);
		
		foreach ($types as $type) {
			$type = strtolower($type);
			$this->writeTypes[$type] = $type;
		}
	}

	public function setWriteMaxSize($max) {
		if ($max < 0) throw new RunTimeException('max size must >= 0');
		$this->writeMaxSize = $max;
	}

	public function setWriteMaxFiles($max) {
		if ($max < 0) throw new RunTimeException('max size must >= 0');
		$this->writeMaxFiles = $max;
	}
}