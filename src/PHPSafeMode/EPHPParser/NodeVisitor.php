<?php

class PHPSafeMode_EPHPParser_NodeVisitor extends PHPParser_NodeVisitorAbstract {
	private $tokens;
	private $printer;

	private $newCode;

	private $appends = array();
	private $replaces = array();
	
	public function __construct($code, PHPParser_PrettyPrinterAbstract $printer) {
		$this->tokens = @token_get_all($code);
		
		$this->printer = $printer;
	}

	public function getNewCode() {
		return $this->newCode;
	}
	
	public function beforeTraverse(array $nodes) {
	}
	
	public function enterNode(\PHPParser_Node $node) {
	}
	
	public function leaveNode(\PHPParser_Node $node) {
		$startPos = $node->getAttribute('startPos');
		$endPos = $node->getAttribute('endPos');
		$operate = $node->getAttribute('operate');

		if ($operate === 'append') {
			$afterPos = $node->getAttribute('afterPos');
			if (!isset($this->appends[$afterPos])) $this->appends[$afterPos] = array();
			$this->appends[$afterPos][] = $this->generateCode($node);
			return ;
		} elseif ($operate === 'replace') {
			$this->replaces[$startPos . '-' . $endPos] = array(
				'start' => $startPos, 
				'end' => $endPos,
				'code' => $this->generateCode($node)
			);
			return ;
		}

		if (null === $startPos || null === $endPos) return ;
		
		if ($startPos !== $endPos) {
			return ;
		}

		if ($operate === 'remove') {
			$this->tokens[$startPos] = '';
			return ;
		}
		
		$this->tokens[$startPos] = $this->generateCode($node);
	}

	private function generateCode(\PHPParser_Node $node) {
		return $this->printer->{'p' . $node->getType()}($node);
	}
	
	public function afterTraverse(array $nodes) {
		$str = '';
		$replace = null;

		foreach ($this->tokens as $pos => $token) {
			if ($replace && $pos > $replace['end']) $replace = null;
			if (!$replace) $replace = $this->isPosReplaced($pos);

			if ($replace) {
				$str .= $replace['start'] == $pos ? $replace['code'] : '';
			} else {
				$str .= is_string($token) ? $token : $token[1];
			}

			if (!isset($this->appends[$pos])) {continue;}

			foreach ($this->appends[$pos] as $code) {
				$str .= ' ' . $code . '; ';
			}
		}
		$this->newCode = $str;
return ;
var_dump($this->replaces);
var_dump($this->tokens);
var_dump($nodes);
var_dump($this->appends);
exit;
	}

	private function isPosReplaced($pos) {
		if (!$this->replaces) return ;

		foreach ($this->replaces as $item) {
			if ($pos >= $item['start'] && $pos <= $item['end']) {
				return $item;
			}
		}
	}
}