<?php
$str = '';
$base = str_repeat('a', 1024);
for ($i=0; $i <2048; $i++) {
	$str .= $base;
}