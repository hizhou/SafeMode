<?php
$str = str_repeat('a', 1024);

$fp = fopen('some2.txt', "a+");
for($i = 1; $i <= 1024; $i++) {
	fwrite($fp, $str);
}
fclose($fp);

echo file_put_contents('some3.txt', str_repeat('b', 1024*1024));

var_dump(copy('some3.txt', 'copy_some4.txt'));