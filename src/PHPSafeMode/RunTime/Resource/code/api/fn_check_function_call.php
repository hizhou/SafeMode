<?php
function fn_check_function_call() {
	$params = func_get_args();
	$functionName = trim(strtolower($params[0]), '\\');
	$callingFile = $params[1];
	$namespace = trim(strtolower($params[2]), '\\');
	if ($namespace != '') {
		$tmp = ($namespace . '\\' . $functionName);
		if (function_exists($tmp)) $functionName = $tmp;
	}
	unset($params[0], $params[1], $params[2]);

	if (!function_exists($functionName)) {
		trigger_error('函数 ' . $functionName . ' 不存在', E_USER_ERROR);
	}

	$newParams = array();
	foreach ($params as $v) {
		$newParams[] = $v;
	}
	$params = $newParams;
		
	$disables = fn_get_disabled_functions();
	$replaces = fn_get_replaced_functions();
		
	if (in_array($functionName, $disables)) {
		trigger_error('函数 ' . $functionName . ' 被禁用', E_USER_ERROR);
	}
	
	foreach (fn_get_function_call_filters() as $filter) {
		$filtered = $filter($functionName, $callingFile, $params);
		if (is_array($filtered)) $params = $filtered;
	}
	
	if (isset($replaces[$functionName])) {
		$functionName = "\\" . $replaces[$functionName];
	}
	return \call_user_func_array($functionName, $params);
}