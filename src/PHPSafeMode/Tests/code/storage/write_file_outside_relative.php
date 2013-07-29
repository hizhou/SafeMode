<?php
$dir = basename(dirname(__DIR__));
$dir = '../../' . $dir;

$file = $dir . '/somefile1.txt';

$fp = fopen($file, 'a+');
fwrite($fp, 'xxxxxxxxxxx');