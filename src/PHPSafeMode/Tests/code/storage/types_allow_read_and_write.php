<?php
function readwritefile($file) {
	$fp = fopen($file, 'a+');
	fwrite($fp, 'xxxdfsdfsdf');
	fclose($fp);
	var_dump(file_get_contents($file));
}

$dir = basename(__DIR__);

readwritefile('../' . $dir . '/somefile1.txt');
readwritefile(__DIR__ . '/somefile2.md');