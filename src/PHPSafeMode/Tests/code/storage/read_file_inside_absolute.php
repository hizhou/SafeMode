<?php
function readtest($file) {
	var_dump(file_get_contents($file));
}

readtest(__DIR__ . '/' . 'somefile1.txt');
readtest(__DIR__ . '/' . 'somefile2.php');

