<?php
namespace PHPSafeMode\Setting;

use PHPSafeMode\SafeMode;

interface ISafeModeSetting {
	public function make(SafeMode $mode);
}