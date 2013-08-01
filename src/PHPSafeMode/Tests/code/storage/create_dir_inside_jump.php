<?php
if (rand(0, 1)) {
	mkdir(__DIR__ . '/jumpdir/somedir11', 0777, 1);
} else {
	$dir = basename(__DIR__);
	mkdir('../'.$dir.'/jumpdir/somedir12', 0777, 1);
}