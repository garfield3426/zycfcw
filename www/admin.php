<?php

$sys_time_start = array_sum(explode(' ', microtime()));

define('APP_ID',    'backend');
include_once '../sys/boot.php';

Core::run();

//echo "<div align='center'>执行用时:".round((array_sum(explode(' ', microtime())) - $sys_time_start) * 1000, 2)." ms</div>";
?>
