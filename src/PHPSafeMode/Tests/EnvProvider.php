<?php
namespace PHPSafeMode\Tests;

class EnvProvider {
	private $dataPath;
	
	public function __construct() {
		$this->dataPath = __DIR__ . '/data';
		$this->checkDirExist($this->dataPath);
	}
	
	public function getSafePath() {
		return $this->getSubPath('rewrite');
	}
	
	public function getBootstrapPath() {
		return $this->getSubPath('bootstrap');
	}
	
	public function getOriginPath() {
		return $this->getSubPath('origin');
	}
	
	
	
	public function clearSafePath() {
		$this->clearDir($this->getSafePath());
	}
	
	public function clearBootstrapPath() {
		$this->clearDir($this->getBootstrapPath());
	}
	
	public function clearOriginPath() {
		$this->clearDir($this->getOriginPath());
	}
	
	
	
	private function getSubPath($dirName) {
		$subPath = $this->dataPath . '/' . $dirName;
		$this->checkDirExist($subPath);
		return $subPath;
	}
	
	private function checkDirExist($dir) {
		if (!is_dir($dir)) {
			mkdir($dir);
		}
	}
	
	private function clearDir($dir) {
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if (in_array($file, array('.', '..'))) {continue;}
				unlink($dir . '/' . $file);
			}
			closedir($handle);
		}
	}
}