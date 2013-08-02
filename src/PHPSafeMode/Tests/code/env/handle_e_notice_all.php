<?php
error_reporting(E_ALL);

$a = $b;

trigger_error('user can not do something here', E_USER_NOTICE);