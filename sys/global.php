<?php
/**
 * 框架内置函数
 *
 * @author yeahoo2000@gmail.com, Try.catch.u@gmail.com
 * @date 21/02/2008
 */

/**
 * 显示指定变量的内容 
 *
 * @param mixd $var 变量名
 * @param string $label 标签名
 */
function vardump($var, $label = null){
    $content = "<hr /><pre>\n";
    if($label){
        $content .= "<strong>$label :</strong>\n";
    }
    $content .= htmlspecialchars(print_r($var, true));
    $content .= "\n</pre><hr />\n";
    echo $content;
}

/**
 * 将回调函数作用到给定数组的单元上,支持多维数组.
 *
 * @param string $callback 回调函数名
 * @param array $array 数组
 *
 * @return array
 *
 */
function arrayMap($callback, $array){
    if(!is_array($array)){
        trigger_error(_Error(0x000104, array(__FUNCTION__, '$array')));
    }
    foreach($array as $key=>$val){
        if(is_array($val)){
            $array[$key] = arrayMap($callback, $array[$key]);
        }else{
            $array[$key] = $callback($array[$key]);
        }
    }
    return $array;
}

/**
 * 合并数组,支持多维数组.
 * @return array
 */
function arrayMerge(){
    $args = func_get_args();
    for($i=count($args)-1; $i>0; $i--){
        if(!is_array($args[$i])){
            trigger_error('args['.($i+1).'] is not a array.');
        }
        foreach($args[$i] as $key=>$val){
            if(is_array($args[$i][$key])){
                $args[$i-1][$key] = arrayMerge($args[$i-1][$key], $args[$i][$key]);
            }else{
                $args[$i-1][$key] = $args[$i][$key];
            }
        }
    }
    return $args[0];
}

/**
 * 对变量进行 addslashes 处理,支持多维数组.
 *
 * @param mixd $vars
 *
 * @return mixd
 */
function addQuotes($var){
    return is_array($var) ? array_map(__FUNCTION__, $var) : addslashes($var);
}
/**
 * 对变量进行 utf8_encode 处理,支持多维数组.
 *
 * @param mixd $vars
 *
 * @return mixd
 */
function utf8Encode($var){
    return is_array($var) ? arrayMap(__FUNCTION__, $var) : utf8_encode($var);
}

/**
 * 对变量进行 utf8_decode 处理,支持多维数组.
 *
 * @param mixd $vars
 *
 * @return mixd
 */
function utf8Decode($var){
    return is_array($var) ? arrayMap(__FUNCTION__, $var) : utf8_decode($var);
}

/**
 * 对变量进行 stripslashes 处理,支持多维数组.
 *
 * @param mixd $vars
 *
 * @return mixd
 */
function stripQuotes($var){
    return is_array($var) ? arrayMap(__FUNCTION__, $var) : stripslashes($var);
}

/**
 * 对变量进行 trim 处理,支持多维数组.
 *
 * @param mixd $vars
 *
 * @return mixd
 */
function trimArr($var){
    return is_array($var) ? arrayMap(__FUNCTION__, $var) : trim($var);
}

/**
 * 对变量进行 nl2br 和 htmlspecialchars 操作,支持多维数组.
 *
 * @param mixd $vars
 *
 * @return mixd
 */
function textFormat($var){
    return is_array($var) ? arrayMap(__FUNCTION__, $var) : nl2br(htmlspecialchars($var));
}

/**
 * 调用插件式函数,插件式函数定义在 SYS_DIR/ext 中
 *
 * @param string $name 函数名
 *
 * @return function
 */
function ext($name){
    $fileName = SYS_DIR."/ext/ext.$name.php";
    if(!file_exists($fileName)){
        trigger_error(_Error(0x000102, array(__FUNCTION__, "ext_$name()", $fileName)));
    }
    include_once $fileName;
    $args = func_get_args();
    unset($args[0]);
    return call_user_func_array("ext_$name", $args);
}

/**
 * 获得一个连接的数据库操作句柄(程序退出时会自动Close)
 *
 * @return object
 */
function getDb(){
    static $instance;
    $adoIncFile = LIB_DIR.'/adodb/adodb.inc.php';
    $adoEHFile = LIB_DIR.'/adodb/adodb-errorhandler.inc.php';
    if(!file_exists($adoIncFile)){
        trigger_error(_Error(0x000102, array(__FUNCTION__, 'Adodb', $adoIncFile)));
    }
    if(!file_exists($adoEHFile)){
        trigger_error(_Error(0x000102, array(__FUNCTION__, 'Adodb', $adoEHFile)));
    }
    if(!isset($instance)){
        include_once($adoIncFile);
        include_once($adoEHFile);
        $conf= $GLOBALS['fw_config']->get('database');
        $ADODB_FETCH_MODE= ADODB_FETCH_ASSOC;
        $instance =  ADONewConnection($conf['driver']);
        if(!$instance->Connect($conf['host'], $conf['user'], $conf['pass'], $conf['db'])){
            trigger_error(_Error(0x000103, array(__FUNCTION__)));
        }
        //在程序结束时自动断开连接,释放数据库资源
        register_shutdown_function(array($instance, 'Close'));
        if($conf['encoding']){
            $instance->Execute("set names '".$conf['encoding']."'");
        }
        //$instance->debug = true;
    }
    return $instance;
}

/**
 * 得到客户端IP地址 
 *
 * @return string
 */
function clientIp(){
    if(getenv('HTTP_CLIENT_IP')){
        $ip= getenv('HTTP_CLIENT_IP');
    }
    elseif(getenv('HTTP_X_FORWARDED_FOR')){
        list($ip)= explode(',', getenv('HTTP_X_FORWARDED_FOR'));
    }
    elseif(getenv('REMOTE_ADDR')){
        $ip= getenv('REMOTE_ADDR');
    }else{
        $ip= $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

/**
 * 将HTML文本里的所有资源链接方式从相对路径转成绝对路径
 *
 * @param string $content HTML文本
 * @return HTML文本
 */
function relativeToAbsolute($content){
    if(is_array($content)){
        $content = array_map(__FUNCTION__, $content);
    }else{
        $url_fix = $GLOBALS['fw_config']->get('url_fix');
        $upload_dir = $GLOBALS['fw_config']->get('upload_dir');

        $content = str_replace("href='$upload_dir", "href='$url_fix$upload_dir", $content);
        $content = str_replace('href="'.$upload_dir, 'href="'.$url_fix.$upload_dir, $content);
        $content = str_replace("src='$upload_dir", "src='$url_fix$upload_dir", $content);
        $content = str_replace('src="'.$upload_dir, 'src="'.$url_fix.$upload_dir, $content);
    }
    return $content;
}

/**
 * 将HTML文本里的所有资源链接方式从绝对路径转成相对路径
 *
 * @param string $content HTML文本
 * @return HTML文本
 */
function absoluteToRelative($content){
    if(is_array($content)){
        $content = array_map(__FUNCTION__, $content);
    }else{
        $url_fix = $GLOBALS['fw_config']->get('url_fix');
        $content = str_replace("href='$url_fix", "href='", $content);
        $content = str_replace('href="'.$url_fix, 'href="', $content);
        $content = str_replace("src='$url_fix", "src='", $content);
        $content = str_replace('src="'.$url_fix, 'src="', $content);
    }
    return $content;
}

/**
 * 取得已上传图片的索引图路径,如果不存在则自动生成(只对上传目录中的图片进行处理)
 *
 * @param string $oImage 原始图片路径(只接收UPLOAD_DIR目录下的路径)
 * @param int maxWidth 生成索引图的最大宽度
 * @param int maxHeight 生成索引图的最大高度
 * @param int quality 生成索引图的质量(0-100)
 *
 * @return string 索引图片的路径
 */
function getSmallImg($oImage, $maxWidth= 150, $maxHeight= 150, $quality= 75){
    $oImage = urldecode($oImage);
    if(!preg_match("!^".$GLOBALS['fw_config']->get('upload_dir')."!", $oImage)){
        return $oImage;
    }
    $image= preg_replace("!^".$GLOBALS['fw_config']->get('upload_dir')."!", '', $oImage);
    $image= $GLOBALS['fw_config']->get('upload_dir')."/s/".$maxWidth.'_'.$maxHeight.$image;
    $fullpath= WEB_DIR.'/'.$image;
    if(file_exists($fullpath)){
        return $image;
    }
    if(!file_exists($oImage)){		//如果原始图片文件已丢失
        return $oImage;				//则不作处理直接返回
    }
    list($width, $height, $type) = getimagesize($oImage);
    $xRatio= $maxWidth / $width;
    $yRatio= $maxHeight / $height;
    if(($width <= $maxWidth) &&($height <= $maxHeight)){
        $tnWidth= $width;
        $tnHeight= $height;
    }
    elseif(($xRatio * $height) < $maxHeight){
        $tnHeight= ceil($xRatio * $height);
        $tnWidth= $maxWidth;
    }else{
        $tnWidth= ceil($yRatio * $width);
        $tnHeight= $maxHeight;
    }
    if(IMAGETYPE_JPEG == $type)
        $src= imagecreatefromjpeg($oImage);
    if(IMAGETYPE_GIF == $type){
        if(!function_exists('imagecreatefromgif')){
            Core::log(L_ERROR, __FUNCTION__.'():'.'系统不支持处理GIF图片');
            return $oImage;
        }else
            $src= imagecreatefromgif($oImage);
    }
    if(IMAGETYPE_PNG == $type)
        $src= imagecreatefrompng($oImage);
    $dst= imagecreatetruecolor($tnWidth, $tnHeight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $tnWidth, $tnHeight, $width, $height);
    if(!@ imagejpeg($dst, $fullpath, $quality)){
        if(!Io::mkdir($fullpath))
            return $oImage;
        else if(!@ imagejpeg($dst, $fullpath, $quality)){
            Core::log(L_ERROR, __FUNCTION__.'():'.'创建索引图片失败:');
        }
    };
    @imagedestroy($src);
    @imagedestroy($dst);
    return $image;
}


if(!function_exists('json_encode')){
    
    require_once LIB_DIR.'/JSON.php';

    
    function json_encode($data){
        $json = new Services_JSON();
        return $json->encode($data);
    }
}
if(!function_exists('json_decode')){
    
    require_once LIB_DIR.'/JSON.php';

    
    function json_decode($data){
        $json = new Services_JSON();
        return $json->decode($data);
    }
}


function alipay_service($item){
    require_once LIB_DIR."/alipay/alipay_service.php";
    $conf = $GLOBALS['fw_config']->get('alipay');
    //设定物流信息
    if(is_array($logistics = $item['logistics'])){
        $item['logistics_fee'] = number_format($logistics[0]['fee'], 2);
        $item['logistics_type'] = $logistics[0]['type'];
        $item['logistics_fee_1'] = number_format($logistics[1]['fee'], 2);
        $item['logistics_type_1'] = $logistics[1]['type'];
        $item['logistics_fee_2'] = number_format($logistics[2]['fee'], 2);
        $item['logistics_type_2'] = $logistics[2]['type'];
    }else{
        $item['logistics_fee'] = number_format($conf['logistics_fee'], 2);
        $item['logistics_type'] = $conf['logistics_type'];
        $item['logistics_fee_1'] = '';
        $item['logistics_type_1'] = '';
        $item['logistics_fee_2'] = '';
        $item['logistics_type_2'] = '';
    }
    $parameter = array(
        'service' => 'trade_create_by_buyer',
        'partner' => $conf['partner'],
        'return_url' => $conf['return_url'],
        'notify_url' => $conf['notify_url'],
        '_input_charset' => $conf['_input_charset'],
        'subject' => 'Order-'.$item['subject'],
        'body' => $item['body'],
        'out_trade_no' => $item['out_trade_no'],
        'logistics_payment' => $conf['logistics_payment'],
        'logistics_fee' => $item['logistics_fee'],
        'logistics_type' => $item['logistics_type'],
        'logistics_payment_1' => $conf['logistics_payment'],
        'logistics_fee_1' => $item['logistics_fee_1'],
        'logistics_type_1' => $item['logistics_type_1'],
        'logistics_payment_2' => $conf['logistics_payment'],
        'logistics_fee_2' => $item['logistics_fee_2'],
        'logistics_type_2' => $item['logistics_type_2'],
        'price' => $item['price'],
        'payment_type' => '1',
        'quantity' => $item['quanlity'],
        'show_url' => $item['show_url'],
        'seller_email' => $conf['seller_email'],
    );
    $alipay = new alipay_service(
        $parameter,
        $conf['security_code'],
        $conf['sign_type'],
        $conf['transport']
    );
    return $alipay;
}


function alipay_notify(){
    require_once LIB_DIR."/alipay/alipay_notify.php";
    $conf= $GLOBALS['fw_config']->get('alipay');
    $alipay = new alipay_notify(
        $conf['partner'],
        $conf['security_code'],
        $conf['sign_type'],
        $conf['_input_charset'],
        $conf['transport']
    );
    return $alipay;
}

function postProxy($url, $data) {
    $url = parse_url($url);
    if(!$url) return "couldn't parse url";
    if(!isset($url['port'])){ $url['port'] = ""; }
    if(!isset($url['query'])){ $url['query'] = ""; }

    $encoded = "";
    foreach($data as $k=>$v){
        $encoded .= '&'.rawurlencode($k)."=".rawurlencode($v);
    }
    $encoded = substr($encoded, 1);

    $fp = fsockopen($url['host'], $url['port'] ? $url['port'] : 80);
    if (!$fp) return "Failed to open socket to {$url['host']}";

    fputs($fp, sprintf("POST %s%s%s HTTP/1.0\n", $url['path'], $url['query'] ? "?" : "", $url['query']));
    fputs($fp, "Host: {$url['host']}\n");
    fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
    fputs($fp, "Content-length: " . strlen($encoded) . "\n");
    fputs($fp, "Connection: close\n\n");

    fputs($fp, "$encoded\n");

    $line = fgets($fp,1024);
    if (!eregi("^HTTP/1\.. 200", $line)) return;

    $results = ""; $inheader = 1;
    while(!feof($fp)) {
        $line = fgets($fp,1024);
        if ($inheader && ($line == "\n" || $line == "\r\n")) {
            $inheader = 0;
        }
        elseif (!$inheader) {
            $results .= $line;
        }
    }
    fclose($fp);

    return $results;
}


function br2nl($message){
    $message = str_replace("<br>\n","\n",$message);
    $message = str_replace("<Br>","\n",$message);
    $message = str_replace("<BR>","\n",$message);
    $message = str_replace("<bR>","\n",$message);
    $message = str_replace("<br />","\n",$message);
    $message = str_replace("\r\n\r\n","\n",$message);
    $message = str_replace("\n\r\n","\n",$message);
    return $message;
} 

function untextFormat($var){
    return is_array($var) ? array_map(__FUNCTION__, $var) : htmlspecialchars_decode(br2nl($var));
} 
?>
