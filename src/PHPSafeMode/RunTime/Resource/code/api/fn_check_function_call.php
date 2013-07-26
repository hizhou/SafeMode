<?php
function fn_check_function_call() {
	$params = func_get_args();
	$functionName = strtolower($params[0]);
	$callingFile = $params[1];
	unset($params[0], $params[1]);
		
	$disables = fn_get_disabled_functions();
	$replaces = fn_get_replaced_functions();
		
	if (in_array($functionName, $disables)) {
		throw new \Exception("function disabled: " . $functionName);
	}
	
	//
	
	if (isset($replaces[$functionName])) {
		$functionName = "\\" . $replaces[$functionName];
	}
	return \call_user_func_array($functionName, $params);
}