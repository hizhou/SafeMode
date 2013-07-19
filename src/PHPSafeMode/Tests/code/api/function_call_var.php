<?php
function getname() {
	$a = "strtouppe";
	$b = "r";
	return $a . $b;
}

$fun1 = "substr";
$fun2 = getname();

echo $fun1("123", "456");
echo $fun2("echo 3");

