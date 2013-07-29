<?php
function fn_check_storage_filter($functionName, $callingFile, $params) {
	$got = null;
	foreach (fn_get_unsafe_storage_functions() as $opType => $functions) {
		foreach ($functions as $name => $config) {
			if ($name == $functionName) {
				$got = $name;
				break 2;
			}
		}
	}
	if (!$got) return $params;

	$checkCreateDirSafePath = function($callingFile, $paramConfig, $params) {
		if (isset($params[$paramConfig['createDirRecursive']]) && $params[$paramConfig['createDirRecursive']] != false)
			die('can not create Recursively');

		$safePath = fn_get_storage_safe_path();

		$base = realpath(dirname($callingFile));
		$dir = rtrim($params[$paramConfig['createDir']], '\\/');

		if (strpos($dir, "/") === 0 || strpos($dir, ":") === 1) {
			$parentDir = realpath(dirname($dir));
		} else {
			$parentDir = realpath($base . '/' . dirname($dir));
		}
		if (!$parentDir || !is_dir($parentDir)) die('parent dir not exist');

		if (strpos($parentDir, $safePath) !== 0) die('path not safe');

		$params[$paramConfig['createDir']] = $parentDir . '/' . basename($dir);
		return $params;
	};

	$checkRmDirSafePath = function($callingFile, $paramConfig, $params) {
		$safePath = fn_get_storage_safe_path();

		$base = realpath(dirname($callingFile));
		$dir = rtrim($params[$paramConfig['rmDir']], '\\/');

		if (strpos($dir, "/") === 0 || strpos($dir, ":") === 1) {
			$dir = realpath($dir);
		} else {
			$dir = realpath($base . '/' . $dir);
		}
		if (!$dir || !is_dir($dir)) die('path not safe or not exist');

		if (strpos($dir, $safePath) !== 0 || $dir == $safePath) die('path not safe');

		$params[$paramConfig['rmDir']] = $dir;
		return $params;
	};

	$checkReadDirSafePath = function($callingFile, $paramConfig, $params) {
		$safePath = fn_get_storage_safe_path();

		$base = realpath(dirname($callingFile));
		$dir = rtrim($params[$paramConfig['readDir']], '\\/');

		if (strpos($dir, "/") === 0 || strpos($dir, ":") === 1) {
			$dir = realpath($dir);
		} else {
			$dir = realpath($base . '/' . $dir);
		}
		if (!$dir || !is_dir($dir)) die('path not safe or not exist');

		if (strpos($dir, $safePath) !== 0) die('path not safe');

		$params[$paramConfig['readDir']] = $dir;
		return $params;
	};
	
	$checkReadFileSafePath = function($checkReadDirSafePath, $callingFile, $paramConfig, $params) {
		if (isset($paramConfig['includePath']) && isset($params[$paramConfig['includePath']]) && $params[$paramConfig['includePath']] != false)
			die('can not use include path');
		
		$parsed = pathinfo($params[$paramConfig['readFile']]);
		
		$checkedDir = $checkReadDirSafePath($callingFile, array('readDir' => 0), array(0 => $parsed['dirname']));
		$checkedDir = $checkedDir[0];
		
		$params[$paramConfig['readFile']] = $checkedDir . '/' . $parsed['basename'];
		return $params;
	};
	
	$checkWriteFileSafePath = function($checkReadDirSafePath, $callingFile, $paramConfig, $params) {
		if (isset($paramConfig['includePath']) && isset($params[$paramConfig['includePath']]) && $params[$paramConfig['includePath']] != false)
			die('can not use include path');
		
		if ($paramConfig['writeFile'] < 0) return $params;
		
		$parsed = pathinfo($params[$paramConfig['writeFile']]);
		
		$checkedDir = $checkReadDirSafePath($callingFile, array('readDir' => 0), array(0 => $parsed['dirname']));
		$checkedDir = $checkedDir[0];
		
		$params[$paramConfig['writeFile']] = $checkedDir . '/' . $parsed['basename'];
		return $params;
	};
	
	$checkRmFileSafePath = function($checkReadDirSafePath, $callingFile, $paramConfig, $params) {
		$parsed = pathinfo($params[$paramConfig['rmFile']]);
		
		$checkedDir = $checkReadDirSafePath($callingFile, array('readDir' => 0), array(0 => $parsed['dirname']));
		$checkedDir = $checkedDir[0];
		
		$params[$paramConfig['rmFile']] = $checkedDir . '/' . $parsed['basename'];
		return $params;
	};
	
	$checkAllowedTypes = function($paramConfig, $params) {
		$allowed = fn_get_storage_allowed_types();
		if (!$allowed) return ;
		
		$files = array();
		if (isset($paramConfig['readFile'])) {
			$files[] = $params[$paramConfig['readFile']];
		}
		if (isset($paramConfig['writeFile']) && $paramConfig['writeFile'] >= 0) {
			$files[] = $params[$paramConfig['writeFile']];
		}
		
		foreach ($files as $file) {
			$basename = basename($file);
			$pieces = explode('.', $basename);
			unset($pieces[0]);
			foreach ($pieces as $ext) {
				$ext = strtolower($ext);
				if (!in_array($ext, $allowed)) {
					die('file type not allowed: ' . $ext);
				}
			}
		}
	};
	
	$checkWriteMaxSize = function($callingFile, $paramConfig, $params) {
		$maxSizeMegaByte = fn_get_storage_write_max_size();
		if (!isset($paramConfig['writeFile'])) return ;
		if ($maxSizeMegaByte <= 0) return ;
		
		$maxSize = $maxSizeMegaByte * 1024 * 1024;
		$base = realpath(dirname($callingFile));
		
		$hasSize = 0;
		if ($paramConfig['writeFile'] < 0 && isset($paramConfig['filePointer'])) {
			$stat = fstat($params[$paramConfig['filePointer']]);
			$hasSize = $stat['size'];
		} else {
			$file = $params[$paramConfig['writeFile']];
			if (strpos($file, "/") === 0 || strpos($file, ":") === 1) {
			} else {
				$file = $base . '/' . $file;
			}
			if (file_exists($file)) {
				clearstatcache();
				$hasSize = filesize($file);
			}
		}
		
		$dataSize = 0;
		if (isset($paramConfig['writeData'])) {
			if ($paramConfig['writeData'] >= 0) $dataSize = strlen($params[$paramConfig['writeData']]);
		} elseif (isset($paramConfig['writeFromFile'])) {
			$file = $params[$paramConfig['writeFromFile']];
			if (strpos($file, "/") === 0 || strpos($file, ":") === 1) {
			} else {
				$file = $base . '/' . $file;
			}
			if (file_exists($file)) {
				clearstatcache();
				$dataSize = filesize($file);
			}
		}
		
		if ($hasSize > $maxSize || $dataSize > $maxSize)
			die('beyond max file size: ' . $maxSizeMegaByte . 'M');
	};
	
	$checkMaxFiles = function($callingFile, $paramConfig, $params) {
		$maxFiles = fn_get_storage_write_max_files();
		if ($maxFiles <= 0) return ;
		
		if (!isset($paramConfig['writeFile']) && !isset($paramConfig['createDir'])) return ;
		
		$base = realpath(dirname($callingFile));
		$creates = 0;
		
		if (isset($paramConfig['createDir'])) {
			$creates += 1;
		} elseif ($paramConfig['writeFile'] >= 0) {
			$file = $params[$paramConfig['writeFile']];
			
			if (strpos($file, "/") === 0 || strpos($file, ":") === 1) {
			} else {
				$file = $base . '/' . $file;
			}
			
			if (!file_exists($file)) $creates += 1;
		}
		if (!$creates) return ;
		
		$safePath = fn_get_storage_safe_path();
		$hasFiles = __countFilesAndDir($safePath);
		
		if (($hasFiles + $creates) > $maxFiles) die('beyond max files: ' . $maxFiles);
	};

	switch ($opType) {
		case 'createDir':
			$checkMaxFiles($callingFile, $config, $params);
			return $checkCreateDirSafePath($callingFile, $config, $params);
			break;
		
		case 'rmDir':
			return $checkRmDirSafePath($callingFile, $config, $params);
			break;

		case 'readDir':
			return $checkReadDirSafePath($callingFile, $config, $params);
			break;
		
		case 'readFile':
			$checkAllowedTypes($config, $params);
			if (isset($config['writeFile'])) {
				$checkMaxFiles($callingFile, $config, $params);
				$checkWriteMaxSize($callingFile, $config, $params);
				$params = $checkWriteFileSafePath($checkReadDirSafePath, $callingFile, $config, $params);
			}
			return $checkReadFileSafePath($checkReadDirSafePath, $callingFile, $config, $params);
			break;
		
		case 'writeFile':
			$checkAllowedTypes($config, $params);
			$checkMaxFiles($callingFile, $config, $params);
			$checkWriteMaxSize($callingFile, $config, $params);
			return $checkWriteFileSafePath($checkReadDirSafePath, $callingFile, $config, $params);
			break;
		
		case 'rmFile':
			$checkRmFileSafePath($checkReadDirSafePath, $callingFile, $config, $params);
			break;
		
		default:
			return $params;
			break;
	}
}
function __countFilesAndDir($path) {
	$count = 0;
	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle))) {
			if (in_array($file, array('.', '..'))) {continue;}

			$count++;
			
			$subPath = $path . '/' . $file;
			if (is_dir($subPath)) {
				$count += __countFilesAndDir($subPath);
			}
		}
		closedir($handle);
	}
	return $count;
}