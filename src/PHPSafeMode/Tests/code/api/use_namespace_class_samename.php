<?php
namespace Test;

class DateTime {
	public function __construct($p = '') {
		echo  "This is Test//DateTime \r\n $p \r\n";
	}
}

var_dump(new DateTime(111));

// class De extends DateTime {

// }
// var_dump(new De(222));

if(rand(0,1)) {
	echo "extendsss:\r\n";
	class De2 extends \DateTime {

	}
	var_dump(new De2('now', new \DateTimeZone('Asia/Chongqing')));
}else {
	echo "original:\r\n";
	var_dump(new \DateTime('now', new \DateTimeZone('Asia/Chongqing')));
}
