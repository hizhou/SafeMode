<?php
namespace PHPSafeMode\Generator\Type;

class ExpressionGenerator extends BaseGenerator {
	
	public function getResolvedCode() {
		$replaces = array();
		foreach ($this->solution as $original => $replace) {
			if ($original != $replace) $replaces[$original] = $replace;
		}
		return $replaces 
			? str_replace(array_keys($replaces), $replaces, $this->code) 
			: $this->code;
	}
}