<?php

function doopendir($dir) {
	if ($handle = opendir($dir)) {
		while (false !== ($file = readdir($handle))) {
			echo $file . "\r\n";
		}
		closedir($handle);
	}

	var_dump(is_dir($dir));
}

doopendir(__DIR__ . '/somedir1');
doopendir(__DIR__ . '/somedir2');