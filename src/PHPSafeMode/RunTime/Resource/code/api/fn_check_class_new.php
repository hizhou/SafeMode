<?php
function fn_check_class_new() {
	$params = func_get_args();
	$className = trim(strtolower($params[0]), '\\');
	$namespace = trim(strtolower($params[1]), '\\');
	if ($namespace != '') {
		$tmp = ($namespace . '\\' . $className);
		if (class_exists($tmp)) $className = $tmp;
	}

	$disables = fn_get_disabled_classes();

	if (in_array($className, $disables)) {
		trigger_error('类 ' . $className . ' 被禁用', E_USER_ERROR);
	}

	unset($params[0], $params[1]);
	$count = count($params);
	if ($count == 0) {
		return new $className;
	} elseif ($count == 1) {
		return new $className($params[2]);
	} elseif ($count == 2) {
		return new $className($params[2], $params[3]);
	} elseif ($count == 3) {
		return new $className($params[2], $params[3], $params[4]);
	} elseif ($count == 4) {
		return new $className($params[2], $params[3], $params[4], $params[5]);
	} elseif ($count == 5) {
		return new $className($params[2], $params[3], $params[4], $params[5], $params[6]);
	} elseif ($count == 6) {
		return new $className($params[2], $params[3], $params[4], $params[5], $params[6], $params[7]);
	} elseif ($count == 7) {
		return new $className($params[2], $params[3], $params[4], $params[5], $params[6], $params[7], $params[8]);
	} else {
		return new $className($params[2], $params[3], $params[4], $params[5], $params[6], $params[7], $params[8], $params[9]);
	}
	
}