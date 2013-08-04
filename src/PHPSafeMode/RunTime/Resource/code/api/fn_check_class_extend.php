<?php
function fn_check_class_extend($className, $namespace) {
	$className = trim(strtolower($className), '\\');
	$namespace = trim(strtolower($namespace), '\\');
	if ($namespace != '') {
		$tmp = ($namespace . '\\' . $className);
		if (class_exists($tmp)) $className = $tmp;
	}

	$disables = fn_get_disabled_classes();

	if (in_array($className, $disables)) {
		trigger_error('类 ' . $className . ' 被禁用', E_USER_ERROR);
	}
}