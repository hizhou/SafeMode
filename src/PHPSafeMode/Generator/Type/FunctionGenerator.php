<?php
namespace PHPSafeMode\Generator\Type;

use PHPSafeMode\Rewriter\Convertor\FunctionCall;
use PHPSafeMode\Rewriter\Convertor\FileInclude;
use PHPSafeMode\Rewriter\Convertor\ClassNew;
use PHPSafeMode\Rewriter\Convertor\ClassExtendCheck;
use PHPSafeMode\Rewriter\Convertor\ClassMethodCall;

class FunctionGenerator extends BaseGenerator {
	private $type = FunctionType::COMMON;

	public function __construct($specify, $code, $dependency = array(), $type = FunctionType::COMMON) {
		$this->type = $type;
		parent::__construct($specify, $code, $dependency);
	}
	
	public function getResolvedCode() {
		$replaces = array();
		foreach ($this->solution as $original => $replace) {
			if ($original != $replace) $replaces[$original] = $replace;
		}
		return $replaces 
			? str_replace(array_keys($replaces), $replaces, $this->code) 
			: $this->code;
	}
	
	public function getConvertor() {
		$specify = isset($this->solution[$this->specify]) ? $this->solution[$this->specify] : $this->specify;
		
		if ($this->type == FunctionType::FUNCTION_CALL) {
			return new FunctionCall($specify);
		} elseif ($this->type == FunctionType::FILE_INCLUDE) {
			return new FileInclude($specify);
		} elseif ($this->type == FunctionType::CLASS_NEW) {
			return new ClassNew($specify);
		} elseif ($this->type == FunctionType::CLASS_EXTEND_CHECK) {
			return new ClassExtendCheck($specify);
		} elseif ($this->type == FunctionType::CLASS_METHOD_CALL) {
			return new ClassMethodCall($specify);
		}
		return ;
	}
}