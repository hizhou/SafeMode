<?php require_once('E:\www\workspace\SafeMode\src\PHPSafeMode\Tests/data/bootstrap/bootstrap.php'); function getname()
{
    $a = 'ex';
    $b = 'ec';
    return $a . $b;
}
//$fun1 = "exec";
$fun2 = 'e' . 'x' . 'e' . 'c';
$fun3 = safemode_function_call('getname');
//echo $fun1("echo 1");
echo safemode_function_call($fun2, 'echo 2');
echo safemode_function_call($fun3, 'echo 3');