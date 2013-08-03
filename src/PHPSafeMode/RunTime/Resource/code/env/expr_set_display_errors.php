<?php
if (file_exists('/dev/null') 
	&& !isset($_SERVER["SERVER_SOFTWARE"]) 
	&& !isset($_SERVER["SERVER_NAME"])
	&& !isset($_SERVER["SERVER_ADDR"])
	&& !isset($_SERVER["SERVER_PORT"])
	&& !isset($_SERVER["REMOTE_ADDR"])) {
	ini_set('error_log','/dev/null');
}
