<?php
$dir = basename(dirname(__DIR__));
$dir = '../../' . $dir;

$file = $dir . '/somefile1.txt';

var_dump(file_get_contents($file));