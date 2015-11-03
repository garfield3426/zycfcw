<?php


function ext_adversite($sign,$logo=null){
    $db = getDb();
    $tab = $GLOBALS['fw_config']->get('table_prefix').'adversite';
   
    $sqlWhere = " where state=1 and sign='$sign'";
    $sqlWhere.= $logo?" and logo is not null or logo<>' '":' ';
   
    $sql = "select * from {$tab}";
    $rs = $db->getRow($sql.$sqlWhere);
   
    return stripQuotes($rs);
}
?>
