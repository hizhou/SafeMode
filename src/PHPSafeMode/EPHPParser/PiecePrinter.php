<?php

class PHPSafeMode_EPHPParser_PiecePrinter extends PHPParser_PrettyPrinter_Default {
	
    /**
     * Pretty prints a node.
     *
     * @param PHPParser_Node $node Node to be pretty printed
     *
     * @return string Pretty printed node
     */
    public function p(\PHPParser_Node $node) {
    	return parent::p($node); //TODO tmp
    	return str_replace("\n" . $this->noIndentToken, "\n", parent::p($node));
    }
	
	/*
    public function pScalar_String(PHPParser_Node_Scalar_String $node) {
    	if (strpos($node->value, "\n") !== false )  {
    		$str = "\"" . addcslashes($node->value, "\"\\") . "\"";
    		if ($node->getAttribute('startLine') === $node->getAttribute('endLine')) {
    			$str = str_replace(array("\n", "\r"), array("\\n", "\\r"), $str);
    		}
    	} else {
    		$str = '\'' . addcslashes($node->value, '\'\\') . '\'';
    	}
    	return $str;
    }

	// protected function pNoIndent($string) {
	// 	return $string;
	// }

	// protected function pStmts(array $nodes, $indent = false) {
	// 	return parent::pStmts($nodes, $indent);
	// }
	*/
}