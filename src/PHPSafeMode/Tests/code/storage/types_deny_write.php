<?php
function readwritefile($file) {
	$fp = fopen($file, 'a+');
	fwrite($fp, 'xxxdfsdfsdf');
	fclose($fp);
}

$dir = basename(__DIR__);

readwritefile('../' . $dir . '/somefile1.txt.php');
readwritefile(__DIR__ . '/somefile2.md.inc');