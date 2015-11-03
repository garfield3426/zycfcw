<?php


function ext_video_list($start=null,$num=null,$cate=null,$len=null){
    $db = getDb();
    $tab = $GLOBALS['fw_config']->get('table_prefix').'video';
    //$db->debug = true;
    $data = array();
    $sqlWhere = " where state=1 ";
    if($cate){
    	$sqlWhere.= " and cate_id in({$cate}) ";
    }
    $sqlOrder = " order by seq desc,id desc";
    $sql = "select id,name,title,logo,put_time,cate_id from {$tab}";
    $rs = $db->SelectLimit($sql.$sqlWhere.$sqlOrder,$num,$start);
 
    $data['list'] = array();
    while(!$rs->EOF){
    	$len = $len?$len:200;
    	$rs->fields['title_sub'] = utf_substr($rs->fields['title'],$len);
    	//$rs->fields['put_time'] = date('Y-m-d',$rs->fields['put_time']);
        $rs->fields['viewLink'] = Core::getUrl('view', 'video', array('cate_id'=>$rs->fields['cate_id'],'id'=>$rs->fields['id']),'',true);
        $data['list'][] = $rs->fields;
        $rs->MoveNext();
    }
 // print_r($data);
    return stripQuotes($data);
}
?>
