<?php 
namespace PHPSafeMode\RunTime\Resource;

use \PHPSafeMode\RunTime\RunTimeException;
use PHPSafeMode\Generator\Type\FunctionGenerator;

class Storage extends BaseResource {
	private $safePath;

	private $allowedTypes = array();
	private $writeMaxSize = 0;
	private $writeMaxFiles = 0;
	
	public function setSafePath($safePath) { //check all file:read/write dir:read/write
		$this->safePath = realpath($safePath);
		if (!is_dir($this->safePath)) throw new RunTimeException('safe path not exist');

		$this->generateCode();
	}

	public function setAllowedTypes($types) { //check all file:read/write 
		if (!is_array($types)) $types = array($types);
		
		foreach ($types as $type) {
			$type = strtolower($type);
			$this->allowedTypes[$type] = $type;
		}
	}

	public function setWriteMaxSize($max) { //check all file:write
		if ($max < 0) throw new RunTimeException('max size must >= 0');
		$this->writeMaxSize = $max;
	}

	public function setWriteMaxFiles($max) { //check all file:write
		if ($max < 0) throw new RunTimeException('max size must >= 0');
		$this->writeMaxFiles = $max;
	}
	
	
	private function generateCode() {
		$this->runTime()->generatorContainer()->add(
			new FunctionGenerator('fn_get_unsafe_storage_functions', $this->generateCodeFromFile('storage/fn_get_unsafe_storage_functions')),
			new FunctionGenerator('fn_get_storage_safe_path', $this->generateFunctionFromVar('fn_get_storage_safe_path', $this->safePath)),
			new FunctionGenerator('fn_get_storage_allowed_types', $this->generateFunctionFromVar('fn_get_storage_allowed_types', $this->allowedTypes)),
			new FunctionGenerator('fn_get_storage_write_max_size', $this->generateFunctionFromVar('fn_get_storage_write_max_size', $this->allowedTypes)),
			new FunctionGenerator('fn_get_storage_write_max_files', $this->generateFunctionFromVar('fn_get_storage_write_max_files', $this->allowedTypes)),

			new FunctionGenerator('fn_check_storage_filter', $this->generateCodeFromFile('storage/fn_check_storage_filter'), 
				array('fn_get_unsafe_storage_functions', 'fn_get_storage_safe_path', 'fn_get_storage_allowed_types', 'fn_get_storage_write_max_size', 'fn_get_storage_write_max_files'))
		);
		$this->runTime()->api()->addFunctionCallFilter('fn_check_storage_filter');
	}
}
