<?php
function fn_check_function_call() {
	$params = func_get_args();
	$functionName = strtolower($params[0]);
	$callingFile = $params[1];
	unset($params[0], $params[1]);

	$newParams = array();
	foreach ($params as $v) {
		$newParams[] = $v;
	}
	$params = $newParams;
		
	$disables = fn_get_disabled_functions();
	$replaces = fn_get_replaced_functions();
		
	if (in_array($functionName, $disables)) {
		throw new \Exception("function disabled: " . $functionName);
	}
	
	foreach (fn_get_function_call_filters() as $filter) {
//var_dump($functionName . '----before ', $params);
		$filtered = $filter($functionName, $callingFile, $params);
		if (is_array($filtered)) $params = $filtered;
//var_dump('--after-- ', $params, '----end');
	}
	
	if (isset($replaces[$functionName])) {
		$functionName = "\\" . $replaces[$functionName];
	}
	return \call_user_func_array($functionName, $params);
}