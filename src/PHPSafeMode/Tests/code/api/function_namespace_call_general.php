<?php
namespace Test;

function ddd($p = '') {
	echo "This is ddd, in \\Test \r\n $p\r\n";
}

require_once 'forinclude.php';

ddd(111);
\test\ddd(222);

forinclude\bbb(333);
