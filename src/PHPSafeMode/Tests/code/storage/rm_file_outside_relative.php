<?php
$dir = basename(dirname(__DIR__));
$dir = '../../' . $dir;

$file = $dir . '/somefile1.txt';
unlink($file);
