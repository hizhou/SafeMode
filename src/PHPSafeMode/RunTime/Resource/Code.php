<?php 
namespace PHPSafeMode\RunTime\Resource;

class Code extends ResourceBase {
	private $safePath;
	
	public function setSafePath($safePath) {
		$this->safePath = $safePath;
		//check
	}
}