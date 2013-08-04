<?php
namespace Test;

class CCC {
	public function __construct($p = '') {
		echo "This is CCC, in \\Test \r\n $p\r\n";
	}
}

require_once 'forinclude.php';

new CCC(11111);
new \Test\CCC(22222);

use test\Ccc as CCCc;
new CCCC(33333);

new ForInclude\AAA();
