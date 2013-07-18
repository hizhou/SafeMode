<?php 
namespace PHPSafeMode;

use PHPSafeMode\RunTime\RunTime;
use PHPSafeMode\Rewriter\Rewriter;
use PHPSafeMode\Rewriter\Bootstrap;

class SafeMode {
	private $runTime;
	private $bootstrap;
	
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
		
		//...
		//generate bootstrap
		$this->bootstrap()->addCodes($this->runTime()->api()->getBootstrapCodes());
		$this->bootstrap()->addCodes($this->runTime()->code()->getBootstrapCodes());
		$this->bootstrap()->addCodes($this->runTime()->cpu()->getBootstrapCodes());
		$this->bootstrap()->addCodes($this->runTime()->memory()->getBootstrapCodes());
		$this->bootstrap()->addCodes($this->runTime()->storage()->getBootstrapCodes());
		//...
		
		$bootstrapSaveTo = $this->bootstrap()->saveTo($this->bootstrapPath . '/' . $bootstrapSaveTo);
		
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
	
	private function bootstrap() {
		if (!$this->bootstrap) {
			$this->bootstrap = new Bootstrap();
		}
		return $this->bootstrap;
	}
}
