<?php
$dir = basename(__DIR__);


function doopendir($dir) {
	if ($handle = opendir($dir)) {
		while (false !== ($file = readdir($handle))) {
			echo $file . "\r\n";
		}
		closedir($handle);
	}

	var_dump(is_dir($dir));
}
doopendir('../' . $dir);
doopendir('../' . $dir . '/somedir3');
doopendir('../' . $dir . '/somedir4');
doopendir('../' . $dir . '/somedir4/subdir');
