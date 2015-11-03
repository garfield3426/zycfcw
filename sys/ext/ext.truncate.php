<?php



function ext_truncate($str, $len, $endStr= null) {
    if (strlen($str) < $len) {
        return $str;
    }
    $len -= strlen($endStr);
    for ($i= 0; $i < $len; $i ++) {
        if (ord($str[$i]) > 127) {
            $output .= $str[$i].$str[$i +1];
            $i ++;
        } else {
            $output .= $str[$i];
        }
    }
    return $output.$endStr;
}
?>
