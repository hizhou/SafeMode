<?php 
namespace PHPSafeMode\RunTime;

use PHPSafeMode\RunTime\Resource\Cpu;
use PHPSafeMode\RunTime\Resource\Api;
use PHPSafeMode\RunTime\Resource\Code;
use PHPSafeMode\RunTime\Resource\Storage;
use PHPSafeMode\RunTime\Resource\Memory;

class RunTime {
	private $cpu;
	private $api;
	private $code;
	private $storage;
	private $memory;
	
	public function cpu() {
		if (!$this->cpu) {
			$this->cpu = new Cpu();
		}
		return $this->cpu;
	}
	
	public function api() {
		if (!$this->api) {
			$this->api = new Api();
		}
		return $this->api;
	}
	
	public function code() {
		if (!$this->code) {
			$this->code = new Code();
		}
		return $this->code;
	}
	
	public function storage() {
		if (!$this->storage) {
			$this->storage = new Storage();
		}
		return $this->storage;
	}
	
	public function memory() {
		if (!$this->memory) {
			$this->memory = new Memory();
		}
		return $this->memory;
	}
}
