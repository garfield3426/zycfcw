<?php


function ext_hospital_list($num){
    $db = getDb();
    $tab = $GLOBALS['fw_config']->get('table_prefix').'hospital';
    //$db->debug = true;
    $data = array();
    $sqlWhere = " where state=1";
    
    $sqlOrder = " order by id asc";
    $sql = "select web,name from {$tab}";
    $rs = $db->SelectLimit($sql.$sqlWhere.$sqlOrder,$num,0);
    $data['list'] = array();
    while(!$rs->EOF){
       
        $data['list'][] = $rs->fields;
        $rs->MoveNext();
    }
    
    return stripQuotes($data);
}
?>
