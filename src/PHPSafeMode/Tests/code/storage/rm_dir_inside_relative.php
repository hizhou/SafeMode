<?php
$dir = basename(__DIR__);
rmdir('../' . $dir . '/somedir3');
rmdir('../' . $dir . '/somedir4/subdir');
rmdir('../' . $dir . '/somedir4');