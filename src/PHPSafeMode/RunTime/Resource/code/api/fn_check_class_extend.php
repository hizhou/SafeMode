<?php
function fn_check_class_extend($className) {
	$className = strtolower($className);

	$disables = fn_get_disabled_classes();

	if (in_array($className, $disables)) {
		trigger_error('类 ' . $className . ' 被禁用', E_USER_ERROR);
	}
}