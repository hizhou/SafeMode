<?php
namespace PHPSafeMode\Generator\Type;

class FunctionType {
	const COMMON = 0;
	const FUNCTION_CALL = 1;
	const FILE_INCLUDE = 2;
	const CLASS_NEW = 3;
	const CLASS_EXTEND_CHECK = 4;
	const CLASS_METHOD_CALL = 5;
}