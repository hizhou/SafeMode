<?php 
namespace PHPSafeMode;

use PHPSafeMode\RunTime\RunTime;
use PHPSafeMode\Rewriter\Rewriter;
use PHPSafeMode\Rewriter\Bootstrap;
use PHPSafeMode\Rewriter\Convertor\FunctionCall;
use PHPSafeMode\Rewriter\Convertor\FileInclude;

class SafeMode {
	private $runTime;
	
	private $safePath;
	private $bootstrapPath;
	
	public function __construct($safePath, $bootstrapPath) {
		if (!is_dir($safePath)) throw new SafeModeException("Please specify a safe path for SafeMode");
		if (!is_writable($safePath)) throw new SafeModeException("safe path should be writable");
		
		$this->runTime = new RunTime();
		
		$this->runTime()->storage()->setSafePath($safePath);
		$this->runTime()->code()->setSafePath($safePath);
		
		$this->safePath = $safePath;
		$this->bootstrapPath = $bootstrapPath;
	}
	
	public function generateSafeCode($code, $saveTo, $bootstrapSaveTo) {
		$rewriter = new Rewriter($code);
		$bootstrap = new Bootstrap();
		
		//...
		
		//generate bootstrap
		$bootstrap->addCodes($this->runTime()->api()->getBootstrapCodes());
		$bootstrap->addCodes($this->runTime()->code()->getBootstrapCodes());
		$bootstrap->addCodes($this->runTime()->cpu()->getBootstrapCodes());
		$bootstrap->addCodes($this->runTime()->memory()->getBootstrapCodes());
		$bootstrap->addCodes($this->runTime()->storage()->getBootstrapCodes());
		
		//...
		if ($this->runTime()->api()->hasDisableFunctions() || $this->runTime()->api()->hasReplaceFunctions()) {
			$rewriter->addConvertor(new FunctionCall());
		}
		if ($this->runTime()->code()->hasSetSafePath() || $this->runTime()->code()->hasSetIncludeTypes()) {
			$rewriter->addConvertor(new FileInclude());
		}
		
		$bootstrapSaveTo = $bootstrap->saveTo($this->bootstrapPath . '/' . $bootstrapSaveTo);
		
		$code = '<?php ';
		if ($bootstrapSaveTo) $code .= "require_once('$bootstrapSaveTo'); ";
		$code .= $rewriter->generateCode();
		
		$saveTo = $this->safePath . '/' . $saveTo;
		file_put_contents($saveTo, $code);
		
		return $saveTo;
	}
	
	
	
	public function runTime() {
		return $this->runTime;
	}
}
