<?php
namespace PHPSafeMode\RunTime\Resource;

class ResourceBase {
	protected $bootstrapCodes = array();
	
	public function getBootstrapCodes() {
		return $this->bootstrapCodes;
	}
}