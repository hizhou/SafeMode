<?php 
namespace PHPSafeMode\Tests;

abstract class BaseTestCase extends \PHPUnit_Framework_TestCase {
	protected $provider;
	
	protected function getProvider() {
		if (!$this->provider) {
			$this->provider = new Provider();
		}
		return $this->provider;
	}
}
