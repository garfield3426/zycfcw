<?php



function ext_rand_string($len, $scope= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890") {
    srand((double) microtime() * 1000000);
    $str_len= strlen($scope) - 1;
    $string= '';
    for ($i= 0; $i < $len; $i ++) {
        $string .= substr($scope, rand(0, $str_len), 1);
    }
    return $string;
}
?>
