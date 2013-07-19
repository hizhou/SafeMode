<?php 
namespace PHPSafeMode\RunTime\Resource;

use PHPSafeMode\RunTime\RunTimeException;

class Api extends ResourceBase {
	private $disableFunctions = array();
	private $replaceFunctions = array();
	
	public function getBootstrapCodes() {
		if ($this->hasDisableFunctions() || $this->hasReplaceFunctions()) {
			$this->appendBootstrapCode($this->generateSafeFunctionCall(), 'safemode_function_call');
		}
		
		return parent::getBootstrapCodes();
	}
	
	
	
	public function disableFunctions($functions) {
		if (!is_array($functions)) $functions = array($functions);
		
		foreach ($functions as $v) {
			$v = strtolower($v);
			$this->disableFunctions[$v] = $v;
		}
	}
	
	public function hasDisableFunctions() {
		return count($this->disableFunctions);
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
			$this->appendBootstrapCode($newImplCode, $newName);
		}
	}
	
	public function hasReplaceFunctions() {
		return count($this->replaceFunctions);
	}
	
	
	
	private function generateSafeFunctionCall() {
		$disables = var_export($this->disableFunctions, true);
		$replaces = var_export($this->replaceFunctions, true);
		
		return 'function safemode_function_call() {
			$params = func_get_args();
			$functionName = strtolower($params[0]);
			unset($params[0]);
			
			$disables = ' . $disables . ';
			$replaces = ' . $replaces .';
			
			if (in_array($functionName, $disables)) {
				throw new \\Exception("function disabled: " . $functionName);
			} elseif (isset($replaces[$functionName])) {
				$functionName = "\\\\" . $replaces[$functionName];
			}
			return \\call_user_func_array($functionName, $params);
		}
		';
	}
	
	private function parseFunctionName($code) {
		$matches = array();
		$pattern = '/function (.*)\(/Uis';
		preg_match($pattern , $code, $matches);
		return $matches ? trim($matches[1]) : null;
	}
}
