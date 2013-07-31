<?php
namespace PHPSafeMode\Generator;

use PHPSafeMode\Rewriter\Rewriter;

use PHPSafeMode\Generator\Type\BaseGenerator;
use PHPSafeMode\Generator\Type\FunctionGenerator;
use PHPSafeMode\Generator\Type\FunctionType;

class GeneratorContainer {
	/**
	 * @var BaseGenerator[]
	 */
	private $container = array();
	
	public function add(BaseGenerator $generator /* [, ...] */) {
		foreach (func_get_args() as $generator) {
			$this->container[$generator->getSpecify()] = $generator;
		}
	}
	
	public function make($code, $saveTo, $bootstrapSaveTo) {
		$rewriter = new Rewriter($code);
		$bootstrap = new Bootstrap();

		$bootstrap->addCodes('error_reporting(E_ALL);');

		$dependencies = array_keys($this->container);
		$rename = array('prefix' => 'sfmd_');
		
		foreach ($this->container as $specify => $generator) { /* @var $generator BaseGenerator */
			$generator->resolve($this->resolve($specify, $generator->getDependency(), $dependencies, $rename));
			
			$bootstrap->addCodes($generator->generate());
				
			if ($generator instanceof FunctionGenerator) { /* @var $generator FunctionGenerator */
				$convertor = $generator->getConvertor();
				if ($convertor) $rewriter->addConvertor($convertor);
			}
		}
		
		$bootstrapSaveTo = $bootstrap->saveTo($bootstrapSaveTo);
		
		$code = '<?php ';
		if ($bootstrapSaveTo) $code .= "require_once('$bootstrapSaveTo'); ";
		$code .= $rewriter->generateCode();
		
		file_put_contents($saveTo, $code);
		
		return $saveTo;
	}
	
	private function resolve($specify, $dependency, $dependencies, $rename = array()) {
		$prefix = isset($rename['prefix']) ? $rename['prefix'] : '';
		$suffix = isset($rename['suffix']) ? $rename['suffix'] : '';
		
		$solution = array();
		foreach ($dependency as $dependent) {
			if (in_array($dependent, $dependencies)) {
				$solution[$dependent] = $prefix . $dependent . $suffix;;
			}
		}
		$solution[$specify] = $prefix . $specify . $suffix;;
		return $solution;
	}

}
