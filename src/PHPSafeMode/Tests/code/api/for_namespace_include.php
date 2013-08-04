<?php
namespace Test\ForInclude;

class AAA {
	public function __construct($p = '') {
		echo "This is AAA, in Test\\ForInclude \r\n $p \r\n";
	}

	public static function staticcall($p = '') {
		echo "s::This is AAA, in Test\\ForInclude \r\n $p \r\n";
	}
}

function bbb($p = '') {
	echo "This is bbb, in Test\\ForInclude \r\n $p \r\n";
}
