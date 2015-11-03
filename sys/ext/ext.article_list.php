<?php
/*
*文章列表类，调用方法：<?php $art=ext('article_list',3,1,false,false,2,null,100)?>
*$num 显示条数
*$state判断显示条件
*$is_order按排序排序
*$recommend按浏览量排序
*$cate查询该类里的文章
*$not不属于该类的文章array(1,2)
*$len控制描述的长度
*/
function ext_article_list($num, $state=null, $is_order=false, $recommend=false, $cate=null, $not=null, $len=null,$end=null,$img=null){
    include_once LIB_DIR.'/category.class.php';
    $db = getDb();
    $tab = $GLOBALS['fw_config']->get('table_prefix').'article';
    //$db->debug = true;
    if($not) $not = !is_array($not) ? array($not) : $not;
    $recommend = $recommend ? $recommend : '';
    $img = $img ? $img : '';
    $data = array();
    $data['cateTitle'] = $cate ? Category::getTitle($cate) : '';
    $data['moreLink'] = $cate ? Core::getUrl('showlist', 'article', array('cate'=>$cate)) : '';
    $sqlWhere = " where 1";
    $sqlWhere .= $state ? " and state={$state}" : '';
    $sqlWhere .= $cate ? " and cate_id in(".implode(Category::getAllChild($cate),',').")" : '';
    $sqlWhere .= $img ? " and img is not null or img<>' '" : '';
    if(!$not){
    	$sqlWhere .= $is_order ? " and is_order>0" : ' and is_order=0';
    }
    $sqlWhere .= $not ? " and id not in(".implode($not, ',').")" : '';
    if($is_order){
    	$sqlOrder = " order by is_order desc";
    }else{
    	$sqlOrder = " order by is_order desc,put_time desc";
    }
    if($recommend){
    	$sqlOrder = " order by browse desc";
    }
    $sql = "select id, title, put_time , color , describes, is_order ,img from {$tab}";
	if($end){
		$rs = $db->SelectLimit($sql.$sqlWhere.$sqlOrder,$num,$end);
	}else{
		$rs = $db->SelectLimit($sql.$sqlWhere.$sqlOrder,$num,0);
	}
   //echo $sql.$sqlWhere.$sqlOrder;
    $data['list'] = array();
    while(!$rs->EOF){
    	$len = $len?$len:200;
        $rs->fields['describes'] = utf_substr($rs->fields['describes'],$len);
        $rs->fields['viewLink'] = Core::getUrl('view', 'article', array('id'=>$rs->fields['id']),'',true);
        $rs->fields['put_time'] = date($GLOBALS['fw_config']->get('dateFormat') ,$rs->fields['put_time']);
       	$data['list'][] = $rs->fields;
        $rs->MoveNext();
    }
  /* echo "<pre>";
print_r($data);*/
    return stripQuotes($data);
}
?>
