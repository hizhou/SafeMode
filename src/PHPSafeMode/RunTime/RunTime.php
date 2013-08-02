<?php 
namespace PHPSafeMode\RunTime;

use PHPSafeMode\Generator\GeneratorContainer;

class RunTime {
	private $resource;
	private $generatorContainer;
	
	public function __construct($safePath) {
		$this->generatorContainer = new GeneratorContainer();

		$this->env()->setSafePath($safePath);
		$this->storage()->setSafePath($safePath);
		$this->code()->setSafePath($safePath);
	}
	
	/**
	 * @return Resource\Env
	 */
	public function env() {
		return $this->getResource('env');
	}
	
	/**
	 * @return Resource\Cpu
	 */
	public function cpu() {
		return $this->getResource('cpu');
	}

	/**
	 * @return Resource\Api
	 */
	public function api() {
		return $this->getResource('api');
	}

	/**
	 * @return Resource\Code
	 */
	public function code() {
		return $this->getResource('code');
	}

	/**
	 * @return Resource\Storage
	 */
	public function storage() {
		return $this->getResource('storage');
	}

	/**
	 * @return Resource\Memory
	 */
	public function memory() {
		return $this->getResource('memory');
	}
	
	
	public function generatorContainer() {
		return $this->generatorContainer;
	}
	
	
	private function getResource($name) {
		$name = strtolower($name);
		
		if (!isset($this->resource[$name])) {
			$class = __NAMESPACE__ . '\\Resource\\' . ucfirst($name);
			$this->resource[$name] = new $class($this);
		}
		return $this->resource[$name];
	}
}
