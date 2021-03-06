<?php

class PHPSafeMode_EPHPParser_Lexer extends PHPParser_Lexer
{
    /**
     * Fetches the next token.
     *
     * @param mixed $value           Variable to store token content in
     * @param mixed $startAttributes Variable to store start attributes in
     * @param mixed $endAttributes   Variable to store end attributes in
     *
     * @return int Token id
     */
    public function getNextToken(&$value = null, &$startAttributes = null, &$endAttributes = null) {
        $startAttributes = array();
        $endAttributes   = array();

        while (isset($this->tokens[++$this->pos])) {
            $token = $this->tokens[$this->pos];

            if (is_string($token)) {
            	$startAttributes['startPos'] = $this->pos;
            	$endAttributes['endPos'] = $this->pos;
            	
                $startAttributes['startLine'] = $this->line;
                $endAttributes['endLine']     = $this->line;

                // bug in token_get_all
                if ('b"' === $token) {
                    $value = 'b"';
                    return ord('"');
                } else {
                    $value = $token;
                    return ord($token);
                }
            } else {
                $this->line += substr_count($token[1], "\n");

                if (T_COMMENT === $token[0]) {
                    $startAttributes['comments'][] = new PHPParser_Comment($token[1], $token[2]);
                } elseif (T_DOC_COMMENT === $token[0]) {
                    $startAttributes['comments'][] = new PHPParser_Comment_Doc($token[1], $token[2]);
                } elseif (!isset($this->dropTokens[$token[0]])) {
                    $value = $token[1];
                    $startAttributes['startLine'] = $token[2];
                    $endAttributes['endLine']     = $this->line;
                    
                    $startAttributes['startPos'] = $this->pos;
                    $endAttributes['endPos'] = $this->pos;

                    return $this->tokenMap[$token[0]];
                }
            }
        }

        $startAttributes['startLine'] = $this->line;

        // 0 is the EOF token
        return 0;
    }
}
