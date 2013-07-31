<?php
function fn_check_file_include($callingFile, $requiredFile) {
	$safePath = fn_get_file_include_safe_path();
	$allowedTypes = fn_get_file_include_allowed_types();

	$base = realpath(dirname($callingFile));
	$requiredFile = rtrim($requiredFile, '\\/');

	if (strpos($requiredFile, "/") === 0 || strpos($requiredFile, ":") === 1) {
		$file = realpath($requiredFile);
	} else {
		$file = realpath($base . '/' . $requiredFile);
	}

	$errorMsg = '被加载文件 ' . $requiredFile . ' 不存在，或在访问范围之外';
	if (!$file || !file_exists($file)) {
		trigger_error($errorMsg, E_USER_ERROR);
	}
	if (strpos($file, $safePath) === false) {
		trigger_error($errorMsg . ' ', E_USER_ERROR);
	}

	$name = basename($requiredFile);
	$mainExt = strrpos($name, ".") ? substr($name, strrpos($name, ".") + 1) : "";

	if ($allowedTypes && !in_array(strtolower($mainExt), $allowedTypes)) {
		trigger_error('被加载文件类型只允许 *.' . implode(" *.", $allowedTypes), E_USER_ERROR);
	}
		
	return $file;
}