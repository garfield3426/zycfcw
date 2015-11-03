<?php


function ext_doctor_list($num,$len=null){
    $db = getDb();
    $tab = $GLOBALS['fw_config']->get('table_prefix').'doctor';
    $data = array();
    $sqlWhere = " where state=1";
    $sqlOrder = " order by  is_order desc,id desc ";
    $sql = "select * from {$tab}";
    $rs = $db->SelectLimit($sql.$sqlWhere.$sqlOrder,$num,0);
    $data['list'] = array();
    while(!$rs->EOF){
    	$len = $len?$len:200;
        $rs->fields['info'] = utf_substr($rs->fields['info'],$len);
        $rs->fields['viewLink'] = Core::getUrl('view', 'doctor', array('id'=>$rs->fields['id']),'',true);
        $data['list'][] = $rs->fields;
        $rs->MoveNext();
    }
    /*echo "<pre>";
    print_r($data);*/
    return stripQuotes($data);
}
?>
