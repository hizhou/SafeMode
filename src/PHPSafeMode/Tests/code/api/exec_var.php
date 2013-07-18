<?php
function getname() {
	$a = "ex";
	$b = "ec";
	return $a . $b;
}

//$fun1 = "exec";
$fun2 = "e" . "x" . "e" . "c";
$fun3 = getname();

//echo $fun1("echo 1");
echo $fun2("echo 2");
echo $fun3("echo 3");

