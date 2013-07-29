<?php
$dir = basename(__DIR__);


function readtest($file) {
	var_dump(file_get_contents($file));
}


readtest('../' . $dir . '/somefile3.xx');
readtest('../' . $dir . '/somefile4.bb');
mkdir('../' . $dir . '/somedir');
readtest('../' . $dir . '/somedir/somfile5.txt');
