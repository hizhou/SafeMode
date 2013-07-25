<?php
namespace PHPSafeMode\Generator\Type;

use PHPSafeMode\Generator\GeneratorException;

abstract class BaseGenerator {
	
	protected $specify = '';
	
	protected $code;
	
	protected $dependency = array();
	protected $solution = array();
	
	public function __construct($specify, $code, $dependency = array()) {
		$this->specify = $specify;
		$this->code = $code;
		$this->dependency = $dependency;
	}
	
	public function generate() {
		if (!$this->isResolved()) return ;
		return $this->getResolvedCode();
	}
	
	public function getSpecify() {
		return $this->specify;
	}
	
	public function getDependency() {
		return $this->dependency;
	}
	
	public function resolve($solution) {
		$this->solution = $solution;
	}
	
	protected function isResolved() {
		if (!$this->getDependency()) return true;
		
		foreach ($this->getDependency() as $dependent) {
			if (!isset($this->solution[$dependent]))
				throw new GeneratorException($dependent . ' is not resolved in ' .$this->specify);
		}
		return true;
	}
	
	abstract protected function getResolvedCode();
	
}
