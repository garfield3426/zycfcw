<?php
/* echo '<pre>';
print_r($_SERVER); */
if(!isset($_SERVER['SCRIPT_URL']))
{
	$_SERVER['SCRIPT_URL'] = $_SERVER['REQUEST_URI'];
}

if(substr($_SERVER['SCRIPT_URL'],0,6)=='/admin'){
	$sys_time_start = array_sum(explode(' ', microtime()));
	define('APP_ID',    'backend');
	include_once '../sys/boot.php';
	Core::run();
}else if($_SERVER['SCRIPT_URL']=='/' | substr($_SERVER['SCRIPT_URL'],0,6)=='/index' ){
	define('APP_ID', 	'frontend');
	include_once '../sys/boot.php';
	Core::run();
}else{
	header("HTTP/1.1 404 Not Found"); 
	echo @file_get_contents('http://www.zycfcw.com/404.html');
	exit;
}
?>
