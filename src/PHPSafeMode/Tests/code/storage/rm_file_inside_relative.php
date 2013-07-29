<?php
$dir = basename(__DIR__);

unlink('../' . $dir . '/somefile3.xx');
unlink('../' . $dir . '/somefile4.bb');
unlink('../' . $dir . '/somedir/somfile5.txt');
