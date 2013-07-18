<?php 
namespace PHPSafeMode\RunTime\Resource;

class Api extends ResourceBase {
	private $disableFunctions = array();
	
	public function getBootstrapCodes() {
		if ($this->hasDisableFunctions()) {
			$this->appendBootstrapCode($this->generateSafeFunctionCall());
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
	
	
	private function generateSafeFunctionCall() {
		$list = var_export($this->disableFunctions, true);
		
		return 'function safemode_function_call() {
			$params = func_get_args();
			$functionName = $params[0];
			unset($params[0]);
			
			if (in_array($functionName, ' . $list . ')) {
				throw new \Exception("function disabled: " . $functionName);
				//echo $functionName;
			}
			return \call_user_func_array($functionName, $params);
		}
		';
	}
}
