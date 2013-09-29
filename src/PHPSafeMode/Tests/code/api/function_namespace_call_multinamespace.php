<?php
namespace Test1;

function var_dump($var) {
	echo "var_dump in Test1 " . "\r\n";
}

var_dump(111);
//\var_dump(222);

namespace Test2;

function var_dump($var) {
	echo "var_dump in Test2 " . "\r\n";
}

var_dump(333);
\var_dump(444);
