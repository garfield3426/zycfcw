<?php


function ext_category($id){
    $db = getDb();
    $tab = $GLOBALS['fw_config']->get('table_prefix').'category';
    $data = array();
    $sqlWhere = " where id=$id ";
    $sql = "select id,title,title_zh,title_en,description from {$tab}";
    $rs = $db->getRow($sql.$sqlWhere);
    //$rs['viewLink']=Core::getUrl('view', 'doctor', array('id'=>$rs['id']),'',true);

    return stripQuotes($rs);
}
?>
