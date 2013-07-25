<?php
namespace PHPSafeMode\Tool;

class ManualParser {
	private $manual;
	
	public function __construct() {
		$base = realpath(__DIR__ . '/../../../doc/manual/');
		
		$modules = array();
		foreach ($this->getFolders($base) as $folder) {
			foreach ($this->getFolders($base . '/' . $folder) as $module) {
				$module = strtolower($module);
				$modules[$module] = $module;
			}
		}
		
		$defaultModules = $this->parseFile($base . '/default');
		$usedModules = get_loaded_extensions();
		
		$beCheckedModules = array();
		$unknownModules = array();
		foreach (array_merge($defaultModules, $usedModules) as $module) {
			$module = strtolower($module);
			if ($this->isIgnoredModule($module)) {
				continue;
			} elseif (isset($modules[$module])) {
				$beCheckedModules[$module] = $module;
			} else {
				$unknownModules[$module] = $module;
			} 
		}
		//var_dump($beCheckedModules, count($beCheckedModules));
	}
	
	private function isIgnoredModule($module) {
		return in_array($module, array('core', 'standard')) || strpos($module, 'pdo_') !== false;
	}
	
	private function getFolders($path) {
		if (!is_dir($path)) return array();
		
		$folders = array();
		if ($dh = opendir($path)) {
			while (($file = readdir($dh)) !== false) {
				if (is_dir($path . '/' . $file) && !in_array($file, array('.', '..')))
					$folders[] = $file;
			}
			closedir($dh);
		}
		return $folders;
	}
	
	private function parseFile($path) {
		$lines = array();
		foreach (explode("\n", file_get_contents($path)) as $line) {
			$line = trim($line);
			$sepPos = strpos($line, ' ');
			if ($sepPos !== false) {
				$head = substr($line, 0, $sepPos);
				$rest = substr($line, $sepPos + 1);
			} else {
				$head = $line;
			}
			
			$lines[] = $head;
		}
		
		return $lines;
	}
}
