<?php
$dir = '/tmp/';
if ($handle = opendir($dir)) {
	while (false !== ($file = readdir($handle))) {
		if (in_array($file, array('.', '..'))) {continue;}
		echo $file . "\r\n";
	}
	closedir($handle);
}

var_dump(is_dir('/etc/'));