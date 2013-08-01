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
		if (isset($params[$paramConfig['createDirRecursive']]) && $params[$paramConfig['createDirRecursive']] != false) {
			trigger_error('禁止递归创建目录', E_USER_ERROR);
		}

		$safePath = fn_get_storage_safe_path();

		$base = realpath(dirname($callingFile));
		$dir = rtrim($params[$paramConfig['createDir']], '\\/');

		if (strpos($dir, "/") === 0 || strpos($dir, ":") === 1) {
			$parentDir = realpath(dirname($dir));
		} else {
			$parentDir = realpath($base . '/' . dirname($dir));
		}
		
		$errorMsg = '上级目录不存在，或在访问范围之外';
		if (!$parentDir || !is_dir($parentDir)) {
			trigger_error($errorMsg, E_USER_ERROR);
		}
		if (strpos($parentDir, $safePath) !== 0) {
			trigger_error($errorMsg. ' ', E_USER_ERROR);
		}

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
		
		$errorMsg = '目录不存在，或在访问范围之外';
		if (!$dir || !is_dir($dir)) {
			trigger_error($errorMsg, E_USER_ERROR);
		}
		if (strpos($dir, $safePath) !== 0 || $dir == $safePath) {
			trigger_error($errorMsg. ' ', E_USER_ERROR);
		}

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
		
		$errorMsg = '目录不存在，或在访问范围之外';
		if (!$dir || !is_dir($dir)) {
			trigger_error($errorMsg, E_USER_ERROR);
		}
		if (strpos($dir, $safePath) !== 0) {
			trigger_error($errorMsg. ' ', E_USER_ERROR);
		}

		$params[$paramConfig['readDir']] = $dir;
		return $params;
	};
	
	$checkReadFileSafePath = function($checkReadDirSafePath, $callingFile, $paramConfig, $params) {
		if (isset($paramConfig['includePath']) && isset($params[$paramConfig['includePath']]) && $params[$paramConfig['includePath']] != false) {
			trigger_error('禁止使用 include_path 操作文件', E_USER_ERROR);
		}
		
		$parsed = pathinfo($params[$paramConfig['readFile']]);
		
		$checkedDir = $checkReadDirSafePath($callingFile, array('readDir' => 0), array(0 => $parsed['dirname']));
		$checkedDir = $checkedDir[0];
		
		$params[$paramConfig['readFile']] = $checkedDir . '/' . $parsed['basename'];
		return $params;
	};
	
	$checkWriteFileSafePath = function($checkReadDirSafePath, $callingFile, $paramConfig, $params) {
		if (isset($paramConfig['includePath']) && isset($params[$paramConfig['includePath']]) && $params[$paramConfig['includePath']] != false) {
			trigger_error('禁止使用 include_path 操作文件', E_USER_ERROR);
		}
		
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
					trigger_error('操作的文件类型只允许 *.' . implode(" *.", $allowed), E_USER_ERROR);
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
		
		if ($hasSize > $maxSize || $dataSize > $maxSize) {
			trigger_error('文件大小不能超过 ' . $maxSizeMegaByte . 'M', E_USER_ERROR);
		}
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
		
		if (($hasFiles + $creates) > $maxFiles) {
			trigger_error('文件和文件夹总数不能超过 ' . $maxFiles . ' 个', E_USER_ERROR);
		}
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