<?php
function fn_error_handler($errorNo, $errorStr, $errorFile, $errorLine, $errorContext) {
	$safePath = fn_get_env_safe_path();
	$isHide = !(error_reporting() & $errorNo);
	
	$header = '';
	$isExit = false;
	
	switch ($errorNo) {
		case E_PARSE: //
			$header = 'Parse error';
			$isExit = true;
			break;
		case E_ERROR: //
		case E_USER_ERROR:
		case E_RECOVERABLE_ERROR:
		case E_COMPILE_ERROR: //
		case E_CORE_ERROR: //
			$header = 'Fatal error';
			$isExit = true;
			break;
		case E_WARNING:
		case E_USER_WARNING:
		case E_COMPILE_WARNING: //
		case E_CORE_WARNING: //
			$header = 'Warning';
			break;
		case E_NOTICE:
		case E_USER_NOTICE:
			$header = 'Notice';
			break;
		case E_DEPRECATED:
		case E_USER_DEPRECATED:
			$header = 'Deprecated';
			break;
		case E_STRICT:
			$header = 'Strict Standards';
			break;
		default:
			$header = 'Unknown error';
			break;
	}
	
	if (strpos($errorStr, $safePath)) {
		$errorStr = str_replace(array($safePath . '/', $safePath . '\\', $safePath), '', $errorStr);
	}
	
	if (strpos($errorFile, $safePath) === 0) {
		$errorFile = str_replace($safePath, '', $errorFile);
		$errorFile = ltrim($errorFile, '\\/');
		$msg = $header . ' : ' . $errorStr . ' in ' . $errorFile . ' on line ' . $errorLine;
	} else {
		$msg = $header . ' : ' . $errorStr;
	}
	
	if (!$isHide) {
		echo $msg . "\r\n\r\n";
	}
	if ($isExit) {
		exit(1);
	}
}
