<?php
namespace Test;

class CCC {
	public function __construct($p = '') {
		echo "This is CCC, in \\Test \r\n $p\r\n";
	}

	public static function staticcall($p = '') {
		echo "s::This is CCC, in \\Test \r\n $p\r\n";
	}
}

require_once 'forinclude.php';

ccc::staticcall(1111);
\Test\CCC::staticcall(2222);

use test\Ccc as CCCc;
CCCC::staticcall(333);

//\Test\ForInclude\AAA::staticcall(4444);
ForInclude\AAA::staticcall(4444);
