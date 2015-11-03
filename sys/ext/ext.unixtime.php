<?php



function ext_unixtime($date, $time=null, $explode=null){
    $explode = $explode ? $explode : array('-',':');
    $date= explode($explode[0], (string)$date);
    if($time) {
        list($h, $i, $s) = explode($explode[1], $time);
        return mktime($h, $i, $s, $date[1], $date[2], $date[0]);
    }
    return mktime(0, 0, 0, $date[1], $date[2], $date[0]);
}
?>
