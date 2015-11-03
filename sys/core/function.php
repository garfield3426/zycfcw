<?php
	/*
	*
	* 名字:PhpBox($sMessage)
	* 功能:弹出一个提示框，$txt为要弹出的内容。
	* 作者:毛仁奇
	*
	*/
	function PhpBox($sMessage)
	{
	  echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
	  echo "<script language='JavaScript'>alert('".$sMessage."')</script>";
	}
	
/* 	function getMemberName(){
		return session::get('membername');
	} */
	/*
	*
	* 名字:CloseWindow()
	* 功能:关闭窗口
	* 作者:毛仁奇
	*
	*/
	
	function CloseWindow()
	{
	  echo "<script language='JavaScript'>self.close();</script>";
	}
	/*
	*
	* 名字:GotoPage($sPage)
	* 功能:跳转到页面$sPage
	* 作者:毛仁奇
	*
	*/
	function GotoPage($sPage)
	{
	  if($sPage=='h')
	  $sPage="javascript: history.back(1)";
	
	  echo "<script language='JavaScript'>location='".$sPage."';</script>";
	}
	/*
	*
	* 名字:GotoPage($sPage)
	* 功能:跳转到页面$sPage 返回并清空表单的内容
	* 作者:毛仁奇
	*
	*/
	function GotoPageNull($sPage)
	{
	  if($sPage=='h'){
	  	echo "<script language='JavaScript'>location.href = document.referrer;</script>";
	  	
	  }else{
	  	echo "<script language='JavaScript'>location='".$sPage."';</script>";
	  }
	}
	/*
	*
	* 名字:getAllHospital()
	* 功能:获取所有医院的信息
	* 作者:毛仁奇
	*
	*/
	function getAllHospital(){
		$db = getDb();
	    $tab = $GLOBALS['fw_config']->get('table_prefix').'hospital';
	    
	    $sql = "select id, name,web from {$tab} where state<>2";
	    $data = $db->GetAll($sql);
	
	    return stripQuotes($data);
	}
	function createBianhao(){
		$dingdanhao = date("Y-m-dH-i-s");
		$dingdanhao = str_replace("-","",$dingdanhao);
		return $dingdanhao .= rand(1000,999999);
		
	}
	/*
	*
	* 名字:getOneHospital()
	* 功能:获取当前医院的所有信息
	* 作者:毛仁奇
	*
	*/
	function getOneHospital(){
		$db = getDb();
	    $tab = $GLOBALS['fw_config']->get('table_prefix').'hospital';

	    $sql = "select * from {$tab} where id=".CONTENT_SKY;
	    $data = $db->GetRow($sql);
	
	    return stripQuotes($data);
	}

	/*
	*
	* 名字:getHospitalName($id)
	* 功能:根据医院ID获取该医院的名称
	* 作者:毛仁奇
	*
	*/
	function getHospitalName($id){
		$db = getDb();
	    $tab = $GLOBALS['fw_config']->get('table_prefix').'hospital';

	    $sql = "select name from {$tab} where id=$id";
	    $data = $db->GetOne($sql);
	
	    return $data;
	}
	
	/*
	*
	* 名字:getAllHospital()
	* 功能:获取科室的信息
	* 作者:毛仁奇
	*
	*/
	function getAllBranch(){
		$db = getDb();
	    $tab = $GLOBALS['fw_config']->get('table_prefix').'branch';
	    
	    $sql = "select id, name from {$tab} where state<>2";
	    $data = $db->GetAll($sql);
	
	    return stripQuotes($data);
	}
	
	/*
	*
	* 名字:getBranchName($id)
	* 功能:根据医院ID获取该医院的名称
	* 作者:毛仁奇
	*
	*/
	function getBranchName($id){
		$db = getDb();
	    $tab = $GLOBALS['fw_config']->get('table_prefix').'branch';

	    $sql = "select name from {$tab} where id=$id";
	    $data = $db->GetOne($sql);
	
	    return $data;
	}
	
	/*
	*
	* 名字:getCateInfo($id)
	* 功能:根据类ID得出该类的描述，主要用于模板页的顶部描述部分
	* 作者:毛仁奇
	*
	*/
	function getCateInfo($id){
		$db = getDb();
	    $tab = $GLOBALS['fw_config']->get('table_prefix').'category';

	    $sql = "select description from {$tab} where id=$id";
	    $data = $db->GetOne($sql);
	    return $data;
	}

	
	function utf_substr($str,$len)
	{
		for($i=0;$i<$len;$i++)
		{
			$temp_str=substr($str,0,1);
			if(ord($temp_str) > 127)
			{
				$i++;
				if($i<$len)
				{
					$new_str[]=substr($str,0,3);
					$str=substr($str,3);
				}
			}
			else
			{
				$new_str[]=substr($str,0,1);
				$str=substr($str,1);
			}
		}
		return join($new_str);
	}

?>