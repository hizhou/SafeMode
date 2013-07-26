<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPSafeMode\Tool\ManualParser;


$man = new ManualParser();
$parsed = $man->parse();

var_dump(count($parsed['disabledFunctions']) ,
count($parsed['enabledFunctions']) , 
count($parsed['functionsComments']),
count($parsed['disabledClasses']) , 
count($parsed['enabledClasses']) ,
count($parsed['classesComments']));


$fs = $man->parseUnsafeFileSystemFunctions($parsed['functionsComments']);
file_put_contents(__DIR__ . '/fs.php', '<?php return ' . var_export($fs, true) . ';');



/*
$html = '<ul>';
foreach ($parsed['enabledFunctions'] as $v) {
	$append = isset($parsed['functionsComments'][$v]) ? $parsed['functionsComments'][$v] : '';
	$html .= '<li><a target="_blank" href="http://php.net/'.$v.'">'.$v.'</a> '.$append.'</li>';
}
foreach ($parsed['enabledClasses'] as $v) {
	$append = isset($parsed['classesComments'][$v]) ? $parsed['classesComments'][$v] : '';
	$html .= '<li><a target="_blank" href="http://php.net/'.$v.'">'.$v.'</a> '.$append.'</li>';
}
$html .= '</ul>';
file_put_contents(__DIR__ . '/allowed.html', $html);
*/
