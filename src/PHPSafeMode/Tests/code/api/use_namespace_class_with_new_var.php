<?php
namespace Test;

class CCC {
	public function __construct($p = '') {
		echo "This is CCC, in \\Test \r\n $p\r\n";
	}
}

require_once 'forinclude.php';

$name = '\Test\CCC';
new $name(22222);
$name = 'Test\CCC';
new $name(22222);


use test\Ccc as CCCc;

$name = '\test\ForInclude\AAA';
new $name();
