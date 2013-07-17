<?php
namespace PHPSafeMode\Tests;

class Provider {
	private $dataPath;
	
	public function __construct() {
		$this->dataPath = __DIR__ . '/data/';
	}
	
	public function getSafePath() {
		return $this->dataPath . 'rewrite/';
	}
	
	public function getBootstrapPath() {
		return $this->dataPath . 'bootstrap/';
	}
	
	public function getOriginPath() {
		return $this->dataPath . 'origin/';
	}
}