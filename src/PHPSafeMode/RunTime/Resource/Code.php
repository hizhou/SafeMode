<?php 
namespace PHPSafeMode\RunTime\Resource;

use PHPSafeMode\RunTime\RunTimeException;
use PHPSafeMode\Generator\Type\FunctionGenerator;
use PHPSafeMode\Generator\Type\FunctionType;

class Code extends BaseResource {
	private $safePath;
	private $allowedTypes = array();
	
	
	public function setSafePath($safePath) {
		$this->safePath = realpath($safePath);
		if (!is_dir($this->safePath)) throw new RunTimeException('safe path not exist');
		
		$this->generateCode();
	}
	
	public function setIncludeTypes($types = 'php') {
		if (!is_array($types)) {
			if ($types == '') throw new RunTimeException('type should not empty');
			$types = array($types);
		}
		
		foreach ($types as $type) {
			$type = strtolower($type);
			$this->allowedTypes[$type] = $type;
		}
		
		$this->generateCode();
	}
	
	
	
	private function generateCode() {
		$this->runTime()->generatorContainer()->add(
			new FunctionGenerator('fn_check_file_include', $this->generateCodeFromFile('code/fn_check_file_include'), 
				array('fn_get_file_include_safe_path', 'fn_get_file_include_allowed_types'), FunctionType::FILE_INCLUDE),
			new FunctionGenerator('fn_get_file_include_safe_path', $this->generateFunctionFromVar('fn_get_file_include_safe_path', $this->safePath)),
			new FunctionGenerator('fn_get_file_include_allowed_types', $this->generateFunctionFromVar('fn_get_file_include_allowed_types', $this->allowedTypes))
		);
	}
}
