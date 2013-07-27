<?php 
namespace PHPSafeMode\RunTime\Resource;

use PHPSafeMode\RunTime\RunTimeException;
use PHPSafeMode\Generator\Type\FunctionType;
use PHPSafeMode\Generator\Type\FunctionGenerator;

class Api extends BaseResource {
	private $disableFunctions = array();
	private $replaceFunctions = array();

	private $functionCallFilters = array();
	
	public function disableFunctions($functions) {
		if (!is_array($functions)) {
			if ($functions == '') throw new RunTimeException('disable function should not be empty');
			$functions = array($functions);
		}
		
		foreach ($functions as $v) {
			$v = strtolower($v);
			$this->disableFunctions[$v] = $v;
		}
		$this->generateCode();
	}
	
	/**
	 * @param array $replaceMap originalFunctionName => newFunctionCodeString
	 */
	public function replaceFunctions($replaceMap) {
		if (!is_array($replaceMap)) return ;
		foreach ($replaceMap as $originName => $newImplCode) {
			$originName = strtolower($originName);
			
			$newName = strtolower($this->parseFunctionName($newImplCode));
			if (!$newName) throw new RunTimeException('function replace failed: can not parse function name from code');
			
			$this->replaceFunctions[$originName] = $newName;
			$this->runTime()->generatorContainer()->add(
				new FunctionGenerator($newName, $newImplCode)
			);
		}
		$this->generateCode();
	}

	public function addFunctionCallFilter($filter) {
		$this->functionCallFilters[$filter] = $filter;
		
		$this->generateCode();
	}
	
	
	
	private function generateCode() {
		$this->runTime()->generatorContainer()->add(
			new FunctionGenerator('fn_check_function_call', $this->generateCodeFromFile('api/fn_check_function_call'), 
				array('fn_get_function_call_filters', 'fn_get_disabled_functions', 'fn_get_replaced_functions'), FunctionType::FUNCTION_CALL),
			new FunctionGenerator('fn_get_function_call_filters', $this->generateFunctionFromVar('fn_get_function_call_filters', $this->functionCallFilters), $this->functionCallFilters),
			new FunctionGenerator('fn_get_disabled_functions', $this->generateFunctionFromVar('fn_get_disabled_functions', $this->disableFunctions)),
			new FunctionGenerator('fn_get_replaced_functions', $this->generateFunctionFromVar('fn_get_replaced_functions', $this->replaceFunctions), $this->replaceFunctions)
		);
	}
	
	private function parseFunctionName($code) {
		$matches = array();
		$pattern = '/function (.*)\(/Uis';
		preg_match($pattern , $code, $matches);
		return $matches ? trim($matches[1]) : null;
	}
}
