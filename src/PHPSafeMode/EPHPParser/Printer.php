<?php

class PHPSafeMode_EPHPParser_Printer {
	private static $_instance;

	/**
	 * @return PHPSafeMode_EPHPParser_Printer
	 */
	public static function getInstance() {
		if (! self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	private function __construct() {
	}
	
	/**
	 * Pretty prints an array of statements.
	 *
	 * @param PHPParser_Node[] $stmts Array of statements
	 *
	 * @return string Pretty printed statements
	 */
	public function prettyPrint(array $stmts, $code) {
		$traverser = new \PHPParser_NodeTraverser;
		$vistor = new PHPSafeMode_EPHPParser_NodeVisitor($code);
		$traverser->addVisitor($vistor);
		
		$traverser->traverse($stmts);
		return $vistor->getNewCode();
	}
}