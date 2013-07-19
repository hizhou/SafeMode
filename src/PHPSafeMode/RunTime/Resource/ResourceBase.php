<?php
namespace PHPSafeMode\RunTime\Resource;

class ResourceBase {
	protected $bootstrapCodes = array();
	
	public function getBootstrapCodes() {
		return $this->bootstrapCodes;
	}
	
	protected function appendBootstrapCode($codeText, $specify = null) {
		if ($specify !== null) {
			$this->bootstrapCodes[$specify] = $codeText;
		} else {
			$this->bootstrapCodes[] = $codeText;
		}
	}
}