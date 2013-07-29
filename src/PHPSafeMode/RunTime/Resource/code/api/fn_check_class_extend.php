<?php
function fn_check_class_extend($className) {
	$className = strtolower($className);

	$disables = fn_get_disabled_classes();

	if (in_array($className, $disables)) {
		die('class disabled: ' . $className);
	}
}