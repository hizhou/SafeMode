<?php
namespace Test;

function ddd($p = '') {
	echo "This is ddd, in \\Test \r\n $p\r\n";
}

require_once 'forinclude.php';

$name = '\test\ddd';
$name(222);

$name = 'test\ddd';
$name(222009);

$name = '\test\forinclude\bbb';
$name(333);
