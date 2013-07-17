<?php 
namespace PHPSafeMode\RunTime\Resource;

class Storage extends ResourceBase {
	private $safePath;
	
	public function setSafePath($safePath) {
		$this->safePath = $safePath;
		//check
	}
}