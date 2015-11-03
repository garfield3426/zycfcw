<?php
define(CHARSET,'utf-8');
header("content-Type: text/html; charset=".CHARSET);
set_time_limit(0);
//$directory = dirname(__FILE__);
$directory = '.';
if(isset($_REQUEST['val_1']) && isset($_REQUEST['val_2'])){
	$path = trim($_REQUEST['path']) ? stripslashes($_REQUEST['path']) : '.';
	//$p = explode('\\',$path);
	//$path = implode('/',$p);
	$sea = $_REQUEST['s'] ? $_REQUEST['s'] : '0';
	$sfuffix = $_REQUEST['file_suffix'] ? $_REQUEST['file_suffix'] : 'html|htm|php';
	$dirsel = $_REQUEST['dir'] ? $_REQUEST['dir'] : '1';
	$val_1 = trim($_REQUEST['val_1']) ? stripslashes($_REQUEST['val_1']) : '';
	$val_2 = trim($_REQUEST['val_2']) ? stripslashes($_REQUEST['val_2']) : '';
	$sfuffixs = explode('|',$sfuffix);
	if(replace_auto($path,$sfuffixs,$val_1,$val_2,$dirsel,$sea)){result_suc();}
	
}
echo <<<EOD
	<form method="post" action="" id="myform"><br/>
	文件路径：<input type="text" name="path" value="{$directory}" /><select><option value='1'>当前文件夹</option></select>&nbsp&nbsp&nbsp&nbsp<font style="color:red";>*默认为本程序当前根目录</font><br/>
	替换的文件名后缀：<input type="text" name="file_suffix" value="html|htm|php" />&nbsp&nbsp&nbsp<font style="color:red";>*多个文件后缀用'|'进行分隔</font><br/>
	<span>需要替换或搜寻的参数：</span>
	<textarea name="val_1" rows="8" cols="50" id="val_1"></textarea><br/>
	<span>替换后的参数：</span>
	<textarea name="val_2" rows="8" cols="50" id="val_2"></textarea><br/>
	当前目录:<input type="radio" name="dir" value="1" checked /><br/>
	当前目录下的所有目录:<input type="radio" name="dir" value="2" /><br/>
	<input type="button" name="button" value="提交" onclick="check();" />&nbsp&nbsp&nbsp&nbsp
	<input type="reset" name="reset" value="重置" />&nbsp&nbsp&nbsp&nbsp
	<input type="button" name="button" value="搜索" onclick="search();"/>
	<input type="hidden" name="s" id="s" />
	</form>
	<script type="text/javascript">
		function check(){
			if(document.getElementById('val_1').value==''){alert('参数不能为空！');return false;}
			if(document.getElementById('val_2').value==''){alert('参数不能为空！');return false;}
			document.getElementById('s').value='0';
			document.getElementById('myform').submit();
		}
		function search(){
			if(document.getElementById('val_1').value==''){alert('参数不能为空！');return false;}
			document.getElementById('s').value='1';
			document.getElementById('myform').submit();
		}
	</script>
EOD;

/**
**	$dir 为文件路径
**	$s  为文件后缀
**	$v1,$v2		为需要替换前和替换后的参数
**	$sel 为需要替换的文件目录选项
**/

function replace_auto($dir,$s,$v1,$v2,$sel,$search){
	if(is_dir($dir)){
		if($handle =opendir($dir)){
			
			while($pathinfo = readdir($handle)){
			
				if($pathinfo !='.' && $pathinfo != '..'){
					if(is_dir($dir.'/'.$pathinfo)){
						if($sel == 2){
							replace_auto($dir.'/'.$pathinfo,$s,$v1,$v2,$sel,$search);
							//echo $pathinfo."<br/>";
						}
					}else{
						
						$file_info = pathinfo($dir.'/'.$pathinfo);
						foreach($s as $v){
							if($file_info['extension'] == $v){
																		
										$file_path = $dir.'/'.$pathinfo;
										$c = get_content($file_path);
										if(strstr($c,$v1)){	
											if($search != '1'){
											
												$c = str_replace($v1,$v2,$c);
												put_content($file_path,$c);
												done($file_path);
												
											}else{		
												search_file($file_path);
												
											}
										}else{
												//fail($file_path);
												continue;											
										}
							}
						}
					}
						
				}
			}
		}
		@closedir($dir);
		return true;
	}else{
		return false;
	}
}

function get_content($file){
	$content = file_get_contents($file);
	return $content;
}

function put_content($file,$cur){
	 file_put_contents($file,$cur);
	
}

function done($file){
echo '<p ><a style="color:red;" href="'.$file.'" target="_blank">'.$file.'.........................................OK</a></p>';
return true;
}

function fail($file){
	echo '<p><a style="color:black;" href="'.$file.'" target="_blank">'.$file.'........................search fail!</a></p>';
}

function search_file($file){
	echo '<p><a href="'.$file.'" style="color:red;" target="_blank">'.$file.'</a></p>';
}
function result_suc(){
	echo '<p style="color:red;">处理完毕!</p>';
	return true;
}

?>