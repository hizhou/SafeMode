<?php
if (rand(0, 1)) {
	echo "file: ";
	for ($i = 1; $i <= 4; $i++) {
		$file = 'somefile' . $i . '.txt';
		file_put_contents($file, 'xxxxx');
	}
} else {
	echo "dir: ";
	
	for ($i = 1; $i <= 4; $i++) {
		$dir = !isset($dir) ? ('somedir' . $i) : ($dir . '/' . 'somedir' . $i);
		mkdir($dir);
	}
}