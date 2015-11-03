<?php
/***************************************************************
 * 错误处理文件
 ***************************************************************/
set_error_handler('errorHandler');


function errorHandler($errno, $errstr, $errfile, $errline) {
    $errRpt= error_reporting();
    if (($errno & $errRpt) != $errno)
        return;
    $msg= "[$errno] \"$errstr\" in file $errfile ($errline).";
    Core::log(L_ERROR, $msg);
    $pvar = array();
    $pvar['message'] = $msg;
    $pvar['trace'] = debugTrace();
    include(SYS_DIR.'/_Error/error.htm');
    exit(1);
}


function debugTrace() {
    $trace = array();
    if(!function_exists('debug_backtrace')){
        return;
    }
    $index= -1;
    $backtrace = debug_backtrace();
    $row = count($backtrace);
    foreach ($backtrace as $t) {
        $str = '';
        $index ++;
        if($index == 0) continue;
        $str .= str_repeat('　',$row-$index-1);
        if(isset($t['class'])) $str .= $t['class'].$t['type'];
        $str .= $t['function'];
        if(isset($t['args']) && sizeof($t['args']) > 0) $str .= '(...)'; else $str .= '()';
        if(isset($t['file'])) $str .= ' -- in file '.basename($t['file']).' ('.$t['line'].')'; else $str .= '<PHP inner-code>';
        $trace[$index] = $str;
    }
    $trace = array_flip($trace);
    arsort($trace, SORT_NUMERIC);
    reset($trace);
    $trace = array_flip($trace);
    return $trace;
}

/**
 * 根据错误代码返回相应语言的错误信息
 *
 * @param int $errorCode
 * @param array $errorCode
 *
 * @return string
 */
function _Error($errorCode, $format = null){
    static $message = array();
    if (!count($message)){
        $fileName = SYS_DIR.'/_Error/e.'.LANGUAGE.'.php';
        if (!is_readable($fileName)){
            $fileName = SYS_DIR.'/_Error/e.'.$GLOBALS['fw_config']->get('language_default').'.php';
        }
        $message = include($fileName);
    }
    return vsprintf($message[$errorCode], $format);
}
?>
