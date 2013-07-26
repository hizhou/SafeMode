<?php
namespace PHPSafeMode\Tool;

class ManualParser {
	private $manualPath;
	
	public function __construct() {
		$this->manualPath = realpath(__DIR__ . '/../../doc/manual/');
	}
	
	public function parse() {
		$base = $this->manualPath;
		
		$disabledFunctions = $enabledFunctions = array();
		$disabledClasses = $enabledClasses = array();
		$classesComments = array();
		$functionsComments = array();
		
		foreach ($this->getNeedCheckedModules($base) as $module => $subPath) {
			$modulePath = $base . '/' . $subPath;
		
			$file = $modulePath . '/functions';
			if (file_exists($file)) {
				list($lines, $comments) = $this->parseFile($file);
				foreach ($lines as $v) {
					$disabledFunctions[$v] = $v;
				}
				$functionsComments += $comments;
			}
		
			$file = $modulePath . '/functions_allowed';
			if (file_exists($file)) {
				list($lines, $comments) = $this->parseFile($file);
				foreach ($lines as $v) {
					$enabledFunctions[$v] = $v;
					unset($disabledFunctions[$v]);
				}
				$functionsComments += $comments;
			}
		
			$file = $modulePath . '/classes';
			if (file_exists($file)) {
				list($lines, $comments) = $this->parseFile($file);
				foreach ($lines as $v) {
					$disabledClasses[$v] = $v;
				}
				$classesComments += $comments;
			}
		
			$file = $modulePath . '/classes_allowed';
			if (file_exists($file)) {
				list($lines, $comments) = $this->parseFile($file);
				foreach ($lines as $v) {
					$enabledClasses[$v] = $v;
					unset($disabledClasses[$v]);
				}
				$classesComments += $comments;
			}
		}
		return array(
			'disabledFunctions' => $disabledFunctions,
			'enabledFunctions' => $enabledFunctions,
			'functionsComments' => $functionsComments,
			'disabledClasses' => $disabledClasses,
			'enabledClasses' => $enabledClasses,
			'classesComments' => $classesComments,
		);
	}
	
	public function parseUnsafeFileSystemFunctions($functionsComments) {
		$fsFunctions = array();
		$types = array('readFile', 'writeFile', 'rmFile', 'readDir', 'createDir', 'rmDir');
		foreach ($functionsComments as $function => $comment) {
			$comment = json_decode($comment, true);
			
			if (!$comment) { continue; }
			
			$type = '';
			foreach ($types as $k) {
				if (isset($comment[$k])) {
					$type = $k;
					break;
				}
			}
			$fsFunctions[$k][$function] = $comment; //unset($functionsComments[$function]);
		}
		return $fsFunctions;
	}

	private function getNeedCheckedModules($base, $isAll = true) {
		$modules = array();
		foreach ($this->getFolders($base) as $folder) {
			foreach ($this->getFolders($base . '/' . $folder) as $module) {
				$module = strtolower($module);
				$modules[$module] = $folder . '/' . $module;
			}
		}
		
		list($defaultModules, ) = $this->parseFile($base . '/default');
		$usedModules = get_loaded_extensions();
		
		$needCheckedModules = array();
		$unknownModules = array();
		foreach (array_merge($defaultModules, $usedModules) as $module) {
			$module = strtolower($module);
			if ($this->isIgnoredModule($module)) {
				continue;
			} elseif (isset($modules[$module])) {
				$needCheckedModules[$module] = $modules[$module];
			} else {
				$unknownModules[$module] = $module;
			} 
		}

		if ($unknownModules)
			throw new \Exception('unknow modules: ' . implode(",", $unknownModules));

		return $isAll ? $modules : $needCheckedModules;
	}
	
	private function isIgnoredModule($module) {
		return in_array($module, array('core', 'standard', 'sysvmsg', 'sysvsem', 'sysvshm')) 
			|| strpos($module, 'pdo_') !== false;
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
		$comments = array();
		foreach (explode("\n", file_get_contents($path)) as $line) {
			$line = trim($line);
			if ($line == '') {continue;}

			$sepPos = strpos($line, ' ');
			if ($sepPos !== false) {
				$head = substr($line, 0, $sepPos);
				$rest = trim(substr($line, $sepPos));
				$comments[$head] = $rest;
			} else {
				$head = $line;
			}
			
			$lines[] = $head;
		}
		
		return array($lines, $comments);
	}
}
