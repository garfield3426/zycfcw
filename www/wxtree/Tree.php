<?php

	$Conn=mysql_connect("localhost","root","root"); 
	mysql_select_db("gztv");  //打开数据库
	mysql_query("SET NAMES 'gb2312'"); //避免乱码
	$tpye=trim($_GET["tpye"]);
	$action=$_GET["action"];
	$id=$_GET["id"];
	$b=trim($_GET["b"]);
	$name=$_POST["name"];
	$bid=$_POST["bid"];
	switch ($action) {
	case add:
		$sql="INSERT INTO `gztv`.`tvmenu` (`id` ,`bid` ,`name` ,`url` )VALUES (NULL , '$bid', '$name', NULL);"; 
	    $qu=mysql_query($sql,$Conn); 
		if($qu){
		$add="";
		print "<script language='javascript'>alert('添加成功!');window.location='tree.php'</script>";
		exit;
		}
		break;
	case edit:
		$sql="update tvmenu set name='$name', bid='$bid' where id='$id'"; 
	     $qu=mysql_query($sql,$Conn); 
		if($qu){
		$edit="";
		//header ("Location:tree.php");
		echo  "<script language='javascript'>alert('编辑成功!');window.location='tree.php'</script>";
        exit;
		}
		break;
	case del:
		$sql="delete FROM tvmenu WHERE id='$b'"; 
	   $qu=mysql_query($sql,$Conn); 
		if($qu){
		$del="";
		echo  "<script language='javascript'>alert('删除成功!');window.location='tree.php'</script>";
		exit;
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<head> 
<style type="text/css">
<!--
.Menu { COLOR:#000000; FONT-SIZE: 12px; CURSOR: hand;}
</style>
<script type="text/javascript">
function ShowMenu(MenuID) 
	{ 
	if(MenuID.style.display=="none") 
	{ 
	MenuID.style.display=""; 
	} 
	else 
	{ 
	MenuID.style.display="none"; 
	} 
	}
</script>
<title>电视树状目录</title>
</head> 
<body> 
<?php 
	$GLOBALS["id"] =1;
	$jibie=1; 
	$sql="select * from tvmenu where bid=0"; 
	$result=mysql_query($sql,$Conn); 
	
	if(mysql_num_rows($result)>0)
	TreeMenu($Conn,$result,$jibie,$id); 
	
	function TreeMenu($Conn,$result,$jibie)  //进入函数循环
	{ 
	$numrows=mysql_num_rows($result); 
	echo "<table cellpadding='0' cellspacing='0' border='0'>"; 
	for($rows=0;$rows<$numrows;$rows++) 
	{ 
	$menu=mysql_fetch_array($result); 
	$sql="select * from tvmenu where bid=$menu[id]"; 
	$result_sub=mysql_query($sql,$Conn); 
	echo "<tr>"; 
		//如果该菜单项目有子菜单，则添加JavaScript onClick语句 
		if(mysql_num_rows($result_sub)>0) 
		{ 
		echo "<td width='20'><img src='+.gif' border='0'></td>"; 
		echo "<td class='Menu' onClick='javascript:ShowMenu(Menu".$GLOBALS["ID"].");'>"; 
		} 
		else 
		{ 
		echo "<td width='20'><img src='-.gif' border='0'></td>"; 
		echo "<td class='Menu'>"; 
		} 
		if($menu[url]!="") 
		echo "<a href='$menu[url]'>$menu[name]</a> "; 
		else 
		echo "<a href='#'>$menu[name]</a>   <a href='?b={$menu[id]}&tpye=add'> 添加</a>  <a href='?b={$menu[id]}&tpye=edit'> 编辑</a>  <a href='?b={$menu[id]}&action=del'> 删除</a>"; 
		echo "</td> </tr>"; 
		if(mysql_num_rows($result_sub)>0) 
		{ 
		echo "<tr id=Menu".$GLOBALS["ID"]++." style='display:none'>"; 
		echo "<td width='20'> </td>"; 
		echo "<td>"; 
		//将级数加1 
		$jibie++; 
		TreeMenu($Conn,$result_sub,$jibie); 
		$jibie--;
		echo "</td></tr>"; 
		} 
		//显示下一菜单
		} 
		echo "</table>"; 
} 
?> 
<br />
<br />
<?php if ($tpye=="add") { ?>
<table width="551" border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<form action="?tpye=add&action=add" method="post" name="form1">
  <tr>
    <td height="28" colspan="2" align="center" bgcolor="#FFFFFF">添加栏目</td>
  </tr>
  <tr>
    <td width="246" height="28" align="right" bgcolor="#FFFFFF">栏目名称：</td>
    <td width="302" height="28" bgcolor="#FFFFFF"><input name="name" type="text" id="name" /></td>
  </tr>
  <tr>
    <td height="28" align="right" bgcolor="#FFFFFF">所属分类：</td>
    <td height="28" bgcolor="#FFFFFF"><select name="bid">
<?php
    
	$sql="select * from tvmenu"; 
	$que=mysql_query($sql,$Conn); 
    while($rs=mysql_fetch_array($que)){
		if ($rs['id']==$b) {
		   $selected="selected=\"selected\"";
		   }else{
		   $selected="";
		   }
	?>
	
      <option value="<?php echo $rs["id"]; ?>" <?php echo $selected;?>><?php echo $rs["name"]; ?></option>
	 <?php }?>
    </select>
    </td>
  </tr>
  <tr>
    <td height="28" colspan="2" align="center" bgcolor="#FFFFFF"><input type="submit" name="Submit" value="提 交" /></td>
  </tr>
  </form>
</table>
<?php }?>

<?php if ($tpye=="edit") {


	$sql="select * from tvmenu where id='$b'"; 
	//echo $sql ;
	//exit;
	$quea=mysql_query($sql,$Conn); 
	$rsa=mysql_fetch_array($quea);

 ?>
<table width="551" border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<form action="?id=<?php echo $b;?>&tpye=add&action=edit" method="post" name="form1">
  <tr>
    <td height="28" colspan="2" align="center" bgcolor="#FFFFFF">编辑栏目</td>
  </tr>
  <tr>
    <td width="246" height="28" align="right" bgcolor="#FFFFFF">栏目名称：</td>
    <td width="302" height="28" bgcolor="#FFFFFF"><input name="name" type="text" id="name" value="<?php echo $rsa['name'];?>" /></td>
  </tr>
  <tr>
    <td height="28" align="right" bgcolor="#FFFFFF">所属分类：</td>
    <td height="28" bgcolor="#FFFFFF"><select name="bid">
	<?php
	 	$sql="select * from tvmenu where bid<>'$rsa['bid']'"; 
	    $que=mysql_query($sql,$Conn); 
           while($rs=mysql_fetch_array($que)){
		   if ($rs["id"]==$rsa['bid']) {
		   $selected="selected=\"selected\"";
		   }else{
		   $selected="";
		   }
	?>
	
      <option value="<?php echo $rs["id"]; ?>" <?php echo $selected;?>><?php echo $rs["name"]; ?></option>
	 <?php }?>
    </select>
    </select>
    </td>
  </tr>
  <tr>
    <td height="28" colspan="2" align="center" bgcolor="#FFFFFF"><input type="submit" name="Submit" value="提 交" /></td>
  </tr>
  </form>
</table>
<?php }?>

<br />
注："+"为存在子目录，可以展开，"-"为终极分类.
</body> 
</html>

