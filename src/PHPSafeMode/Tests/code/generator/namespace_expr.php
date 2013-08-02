<?php
namespace MySpace;

$x = 'xxx';

use MySpace\B as BBB;

function a() {
	echo 'function a' . "\r\n";
}
\MySpace\a(); //TODO if relative: a();

class b {
	public function __construct() {
		echo 'class b';
	}
}
var_dump(new BBB);
