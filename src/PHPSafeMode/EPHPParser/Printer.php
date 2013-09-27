<?php

class PHPSafeMode_EPHPParser_Printer {
	public function __construct() {
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
		$traverser->addVisitor(new PHPSafeMode_EPHPParser_NodeVisitor($code));
		
		$traverser->traverse($stmts);
	}
}