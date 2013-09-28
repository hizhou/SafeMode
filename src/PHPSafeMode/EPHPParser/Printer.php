<?php

class PHPSafeMode_EPHPParser_Printer extends PHPParser_PrettyPrinter_Default {
	/**
	 * Pretty prints an array of statements.
	 *
	 * @param PHPParser_Node[] $stmts Array of statements
	 *
	 * @return string Pretty printed statements
	 */
	public function prettyPrint(array $stmts, $code) {
		$traverser = new \PHPParser_NodeTraverser;
		$vistor = new PHPSafeMode_EPHPParser_NodeVisitor($code, $this);
		$traverser->addVisitor($vistor);
		
		$traverser->traverse($stmts);
		return $vistor->getNewCode();
	}

    public function pScalar_String(PHPParser_Node_Scalar_String $node) {
    	$str = "\"" . addcslashes($node->value, "\"\\") . "\"";
    	if (strpos($node->value, "\n") !== false )  {
    		if ($node->getAttribute('startLine') === $node->getAttribute('endLine')) {
    			$str = str_replace(array("\n", "\r"), array("\\n", "\\r"), $str);
    		}
    	}
    	return $str;
    	/*
    	$nstr = '\'' . $this->pNoIndent(addcslashes($node->value, '\'\\')) . '\'';
    	$nstr2 = "\"" . addcslashes($node->value, "\"\\") . "\"";
    	if (strpos($node->value, "\n") !== false )  {
    		//var_dump($node,$node->value);
    		if ($node->getAttribute('startLine') == $node->getAttribute('endLine')) {
    			$nstr2 = str_replace(array("\n", "\r"), array("\\n", "\\r"), $nstr2);
    		}
    		var_dump($nstr, $nstr2);
    	}
        return $nstr2;
        */
    }

	// protected function pNoIndent($string) {
	// 	return $string;
	// }

	// protected function pStmts(array $nodes, $indent = false) {
	// 	return parent::pStmts($nodes, $indent);
	// }
}