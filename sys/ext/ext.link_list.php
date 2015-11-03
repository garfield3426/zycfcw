<?php


function ext_link_list($num,$logo=null){
    $db = getDb();
    $tab = $GLOBALS['fw_config']->get('table_prefix').'link';
    //$db->debug = true;
    $data = array();
    $sqlWhere = " where state=1";
    $sqlWhere.= $logo?" and logo is not null or logo<>' '":' ';
    $sqlOrder = " order by  id ";
    $sql = "select * from {$tab}";
    $rs = $db->SelectLimit($sql.$sqlWhere.$sqlOrder,$num,0);
    $data['list'] = array();
    while(!$rs->EOF){
        $rs->fields['logo'] = $rs->fields['logo'];
        $data['list'][] = $rs->fields;
        $rs->MoveNext();
    }
    return stripQuotes($data);
}
?>
