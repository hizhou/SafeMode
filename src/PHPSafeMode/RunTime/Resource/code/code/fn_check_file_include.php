<?php
function fn_check_file_include($mainFile, $requiredFile) {
	if (strpos($requiredFile, "/") === 0 || strpos($requiredFile, ":") === 1) {
		die("be relative");
	}

	$safePath = fn_get_file_include_safe_path();
	$allowedTypes = fn_get_file_include_allowed_types();

	$file = realpath(dirname($mainFile) . "/" . $requiredFile);
	if (!$file || !file_exists($file)) {
		die($requiredFile . " not exist");
	}

	if (strpos($file, $safePath) === false) {
		die("out of dir");
	}
		
	$name = basename($requiredFile);
	$mainExt = strrpos($name, ".") ? substr($name, strrpos($name, ".") + 1) : "";

	if ($allowedTypes && !in_array(strtolower($mainExt), $allowedTypes)) {
		die("type no allowed, only: " . implode(",", $allowedTypes));
	}
		
	return $file;
}