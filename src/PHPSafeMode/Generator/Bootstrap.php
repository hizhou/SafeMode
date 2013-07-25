<?php
namespace PHPSafeMode\Generator;

class Bootstrap {
	private $codes = array();
	
	public function addCodes($codes) {
		if (!is_array($codes)) {
			$this->codes[] = $codes;
			return ;
		}
		
		foreach ($codes as $specify => $code) {
			$this->codes[] = $code;
		}
	}
	
	public function saveTo($path) {
		if (!$this->codes) return ;
		file_put_contents($path, '<?php ' . implode("\r\n", $this->codes));
		return $path;
	}
}