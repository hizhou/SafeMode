<?php
$dir = basename(__DIR__);


function readtest($file) {
	$fp = fopen($file, 'a+');
	fwrite($fp, 'xxxxxxxxxxx');
	fclose($fp);
}


readtest('../' . $dir . '/somefile3.xx');
readtest('../' . $dir . '/somefile4.bb');
mkdir('../' . $dir . '/somedir');
readtest('../' . $dir . '/somedir/somfile5.txt');
