<?php


function ext_ad_list($num){
    $db = getDb();
    $tab = $GLOBALS['fw_config']->get('table_prefix').'ad';
    //$db->debug = true;
    $data = array();
    $sqlWhere = " where state=1";
    
    $sqlOrder = " order by seq asc";
    $sql = "select * from {$tab}";
    $rs = $db->SelectLimit($sql.$sqlWhere.$sqlOrder,$num,0);
    $data['list'] = array();
    while(!$rs->EOF){
        $rs->fields['viewLink'] = Core::getUrl('view', 'article', array('id'=>$rs->fields['id']),'',true);
        $data['list'][] = $rs->fields;
        $rs->MoveNext();
    }
   
    return stripQuotes($data);
}
?>
