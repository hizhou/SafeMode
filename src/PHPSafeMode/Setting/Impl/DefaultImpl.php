<?php
namespace PHPSafeMode\Setting\Impl;

use PHPSafeMode\Setting\ISafeModeSetting;
use PHPSafeMode\SafeMode;
use  PHPSafeMode\Tool\ManualParser;

class DefaultImpl implements ISafeModeSetting {
	private $parsed;
	private $unSafeFileSystemFunctions;

	public function __construct() {
		$parser = new ManualParser();

		$this->parsed = $parser->parse();
		$this->unSafeFileSystemFunctions = $parser->parseUnsafeFileSystemFunctions($this->parsed['functionsComments']);
	}

	public function make(SafeMode $mode) {
		$mode->runTime()->api()->disableFunctions($this->getDisabledFunctions());
		$mode->runTime()->api()->disableClasses($this->getDisabledClasses());
		$mode->runTime()->storage()->setUnSafeStorageFunctions($this->getUnsafeFileSystemFunctions());

		return $mode;
	}

	public function getDisabledFunctions() {
		return $this->parsed['disabledFunctions'];
	}

	public function getDisabledClasses() {
		return $this->parsed['disabledClasses'];
	}

	public function getUnsafeFileSystemFunctions() {
		return $this->unSafeFileSystemFunctions;
	}
}