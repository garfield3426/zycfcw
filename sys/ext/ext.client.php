<?php


function ext_client($num,$type=null){
    $db = getDb();
    $tab = $GLOBALS['fw_config']->get('table_prefix').'client';
    //$db->debug = true;
    $data = array();
    $sqlWhere = " where state=1 and reply<>'0' and h_id=".CONTENT_SKY." ";
    if($type){
    	$sqlWhere.=" and types=$type";
    }
    $sqlOrder = " order by put_time desc";
    $sql = "select id,title,reply,put_time,content from {$tab}";
    $rs = $db->SelectLimit($sql.$sqlWhere.$sqlOrder,$num,0);
 
    $data['list'] = array();
    while(!$rs->EOF){
        $rs->fields['viewLink'] = Core::getUrl('view', 'client', array('id'=>$rs->fields['id']),'',true);
        $data['list'][] = $rs->fields;
        $rs->MoveNext();
    }
    
    return stripQuotes($data);
}
?>
