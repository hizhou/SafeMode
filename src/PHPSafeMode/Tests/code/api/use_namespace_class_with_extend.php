<?php
namespace Test;

class CCC {
	public function __construct($p = '') {
		echo "This is CCC, in \\Test \r\n $p\r\n";
	}
}

require_once 'forinclude.php';

class Ce extends ccc {

}

class Ae extends ForInclude\AAA {

}

new Ce(111);
new Ae(222);
