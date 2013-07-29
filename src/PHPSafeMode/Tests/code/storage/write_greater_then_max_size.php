<?php
$str = str_repeat('a', 1024);

$file = 'some1.txt';

$r = rand(0, 2);

if ($r == 0) {
	echo "fopen: ";
	$fp = fopen($file, "a+");
	for($i = 1; $i <= 1026; $i++) {
		fwrite($fp, $str);
	}
	fclose($fp);
} elseif ($r == 1) {
	echo "file_put_contents: ";
	file_put_contents($file, str_repeat($str, 1024) . 'x');
} elseif ($r == 2) {
	echo "copy: ";
	
	$fp = fopen($file, "a+");
	for($i = 1; $i <= 1025; $i++) {
		fwrite($fp, $str);
	}
	
	copy($file, 'copy_' . $file);
}