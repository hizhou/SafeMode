<?php
function readtest($file) {
	$fp = fopen($file, 'a+');
	fwrite($fp, 'xxxxxxxxxxx');
	fclose($fp);
}

readtest(__DIR__ . '/' . 'somefile1.txt');
readtest(__DIR__ . '/' . 'somefile2.php');

