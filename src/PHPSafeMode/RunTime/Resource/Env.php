<?php 
namespace PHPSafeMode\RunTime\Resource;

use PHPSafeMode\RunTime\RunTimeException;
use PHPSafeMode\Generator\Type\ExpressionGenerator;
use PHPSafeMode\Generator\Type\FunctionGenerator;

class Env extends BaseResource {
	private $safePath;
	
	public function setSafePath($safePath) {
		$this->safePath = realpath($safePath);
		if (!is_dir($this->safePath)) throw new RunTimeException('safe path not exist');
		
		$this->runTime()->generatorContainer()->add(
			new FunctionGenerator('fn_get_env_safe_path', $this->generateFunctionFromVar('fn_get_env_safe_path', $this->safePath))
		);
	}
	
	public function setDisplayErrors($isEnable) {
		//For *nix system
		$append = $isEnable ? '' : $this->generateCodeFromFile('env/expr_set_display_errors');

		$isEnable = $isEnable ? "true" : "false";

		$this->runTime()->generatorContainer()->add(
			new ExpressionGenerator('expr_set_display_errors', 'ini_set("display_errors", ' . $isEnable . ');' . $append)
		);
	}
	
	public function setErrorReporting($set) {
		$this->runTime()->generatorContainer()->add(
			new ExpressionGenerator('expr_set_error_reporting', 'error_reporting(' . $set . ');')
		);
	}
	
	public function setErrorHandler($name = '', $code = '') {
		if ('' == $name || '' == $code) {
			$name = 'fn_error_handler';
			$code = $this->generateCodeFromFile('env/' . $name);
		}
		
		$this->runTime()->generatorContainer()->add(
			new FunctionGenerator($name, $code, array('fn_get_env_safe_path')),
			new ExpressionGenerator('expr_set_error_handler', 'set_error_handler("' . $name . '");', array($name))
		);
	}
}