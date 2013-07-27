<?php
$dir = basename(dirname(__DIR__));
$dir = '../../' . $dir;

if ($handle = opendir($dir)) {
	while (false !== ($file = readdir($handle))) {
		if (in_array($file, array('.', '..'))) {continue;}
		echo $file . "\r\n";
	}
	closedir($handle);
}

var_dump(is_dir($dir));