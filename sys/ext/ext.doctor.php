<?php


function ext_doctor($id){
    $db = getDb();
    $tab = $GLOBALS['fw_config']->get('table_prefix').'doctor';
    //$db->debug = true;
    $data = array();
    $sqlWhere = " where state=1 and id=$id ";
    $sql = "select * from {$tab}";
    $rs = $db->getRow($sql.$sqlWhere);
    $rs['viewLink']=Core::getUrl('view', 'doctor', array('id'=>$rs['id']),'',true);

    return stripQuotes($rs);
}
?>
