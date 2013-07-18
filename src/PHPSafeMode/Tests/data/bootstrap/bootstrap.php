<?php function safemode_function_call() {
			$params = func_get_args();
			$functionName = $params[0];
			unset($params[0]);
			
			if (in_array($functionName, array (
  'exec' => 'exec',
))) {
				throw new \Exception("function disabled: " . $functionName);
				//echo $functionName;
			}
			return \call_user_func_array($functionName, $params);
		}
		