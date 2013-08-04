<?php
function fn_check_class_method_call() {
	$params = func_get_args();
	$className = trim(strtolower($params[0]), '\\');
	$methodName = $params[1];
	$namespace = trim(strtolower($params[2]), '\\');
	if ($namespace != '') {
		$tmp = ($namespace . '\\' . $className);
		if (class_exists($tmp)) $className = $tmp;
	}

	unset($params[0], $params[1], $params[2]);

	$disables = fn_get_disabled_classes();
	if (in_array($className, $disables)) {
		trigger_error('类 ' . $className . ' 被禁用', E_USER_ERROR);
	}

	return \call_user_func_array(array($className, $methodName), $params);
}