<?php
class Test {
	public function __construct($p1, $p2) {
		echo ' construct ('.$p1.', '.$p2.')';
		$this->ICall();
	}

	public function call() {
		echo ' common-call ';
	}

	public static function Scall() {
		echo ' static-call ';
	}

	private function ICall() {
		echo ' inner-call ';
	}
}

$param3 = 'sidof';
$name = 'test';
$b = new $name(1, $param3);
