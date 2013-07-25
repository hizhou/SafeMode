<?php
namespace PHPSafeMode\Generator\Type;

use PHPSafeMode\Rewriter\Convertor\FunctionCall;
use PHPSafeMode\Rewriter\Convertor\FileInclude;

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
		}
		return ;
	}
}