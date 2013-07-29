<?php
function testreadfile($file) {
	var_dump(file_get_contents($file));
}

$dir = basename(__DIR__);

testreadfile('../' . $dir . '/somefile1.txt.php');
testreadfile(__DIR__ . '/somefile2.md.exe');