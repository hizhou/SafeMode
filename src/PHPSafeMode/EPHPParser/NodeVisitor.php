<?php

class PHPSafeMode_EPHPParser_NodeVisitor extends \PHPParser_NodeVisitorAbstract {
	private $tokens;
	private $printer;

	private $newCode;

	private $appends = array();
	private $replaces = array();
	private $skips = array();
	
	public function __construct($code) {
		$this->tokens = @token_get_all($code);
		$this->printer = new PHPSafeMode_EPHPParser_PiecePrinter();
	}

	public function getNewCode() {
		return $this->newCode;
	}
	
	public function beforeTraverse(array $nodes) {
	}
	
	public function enterNode(\PHPParser_Node $node) {
		if ($node instanceof \PHPParser_Node_Scalar_Encapsed
			|| $node instanceof \PHPParser_Node_Scalar_String
			|| $node instanceof \PHPParser_Node_Stmt_HaltCompiler
			|| $node instanceof \PHPParser_Node_Stmt_InlineHTML
		) {
			$startPos = $node->getAttribute('startPos');
			$endPos = $node->getAttribute('endPos');
			
			$this->skips[$startPos . '-' . $endPos] = array(
				'start' => $startPos, 
				'end' => $endPos,
			);
		}
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
		
		if ($this->isPosSkiped($startPos)) {
			return ;
		}

		if ($operate === 'remove') {
			$this->tokens[$startPos] = '';
			return ;
		}
		
		$this->tokens[$startPos] = $this->generateCode($node);
	}

	private function generateCode(\PHPParser_Node $node) {
		return $this->printer->p($node);
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
	
	private function isPosSkiped($pos) {
		if (!$this->skips) return false;
		
		foreach ($this->skips as $item) {
			if ($pos >= $item['start'] && $pos <= $item['end']) {
				return true;
			}
		}
		return false;
	}
}