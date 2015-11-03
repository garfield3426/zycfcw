<?php
echo '<pre>';
var_dump($_SERVER);
$file1 = "/pkg/web/www.purui.cn/www/n/js.html";
$file2 = "/pkg/web/www.purui.cn/www/js.html";

if(abs(@filesize($file1))!=abs(@filesize($file2))){
	copy($file1,$file2);
}
?>
