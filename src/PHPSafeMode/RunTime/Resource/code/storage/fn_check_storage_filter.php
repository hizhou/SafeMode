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

	switch ($opType) {
		case 'createDir':
			return $checkCreateDirSafePath($callingFile, $config, $params);
			break;
		
		case 'rmDir':
			return $checkRmDirSafePath($callingFile, $config, $params);
			break;

		case 'readDir':
			return $checkReadDirSafePath($callingFile, $config, $params);
			break;
		
		case 'readFile':
			# code...
			break;
		
		case 'writeFile':
			# code...
			break;
		
		case 'rmFile':
			# code...
			break;
		
		default:
			return $params;
			break;
	}
}