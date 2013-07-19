<?php 
namespace PHPSafeMode\RunTime\Resource;

use PHPSafeMode\RunTime\RunTimeException;

class Code extends ResourceBase {
	private $safePath;
	private $allowedTypes = array();
	
	public function getBootstrapCodes() {
		if ($this->hasSetSafePath() || $this->hasSetIncludeTypes()) {
			$this->appendBootstrapCode($this->generateSafeIncludeFunctionCall(), 'safemode_check_include');
		}
		return parent::getBootstrapCodes();
	}
	
	public function setSafePath($safePath) {
		$this->safePath = $safePath;
		if (!is_dir($this->safePath)) throw new RunTimeException('safe path not exist');
	}
	
	public function setIncludeTypes($types = 'php') {
		if (!is_array($types)) $types = array($types);
		
		foreach ($types as $type) {
			$type = strtolower($type);
			$this->allowedTypes[$type] = $type;
		}
	}
	
	public function hasSetSafePath() {
		return $this->safePath != '';
	}
	
	public function hasSetIncludeTypes() {
		return count($this->allowedTypes);
	}
	
	
	private function generateSafeIncludeFunctionCall() {
		$safePath = var_export($this->safePath, true);
		$allowedTypes = var_export($this->allowedTypes, true);
		
		return 'function safemode_check_include($mainFile, $requiredFile) {
			if (strpos($requiredFile, "/") === 0 || strpos($requiredFile, ":") === 1) {
				die("be relative");
			}

			$safePath = realpath(' . $safePath . ');
			$allowedTypes = ' . $allowedTypes . ';

			$file = realpath(dirname($mainFile) . "/" . $requiredFile);
			if (!$file || !file_exists($file)) {
				die($requiredFile . " not exist");
			}

			if (strpos($file, $safePath) === false) {
				die("out of dir");
			}
			
			$name = basename($requiredFile);
			$mainExt = strrpos($name, ".") ? substr($name, strrpos($name, ".") + 1) : "";

			if ($allowedTypes && !in_array(strtolower($mainExt), $allowedTypes)) {
				die("type no allowed, only: " . implode(",", $allowedTypes));
			}
					
			return $file;
		}
		';
	}
}