<?php



function ext_is_date($date){
    $d_pat = '([1-9])|((0[1-9])|([1-2][0-9])|(3[0-2]))';
    $m_pat = '([1-9])|((0[1-9])|(1[0-2]))';
    $y_pat = '(19|20)[0-9]{2}';
    $pattern="!^($y_pat)-($m_pat)-($d_pat)$!";
    return preg_match($pattern,$date);
}
?>
