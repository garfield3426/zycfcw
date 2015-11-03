<?php
/*-----------------------------------------------------+
 * 框架启动程序
 *
 * @author yeahoo2000@gmail.com
 * @author Try.catch.u@gmail.com
 +-----------------------------------------------------*/
error_reporting(E_ALL);
error_reporting(E_ALL & ~E_NOTICE);
if(!defined('APP_ID')){
    die('缺少必要常量,没有定义APP_ID');
}
if(!defined('SESSION_PATH')){
    define('SESSION_PATH', APP_ID);
}
//是否有指定sessionid?有的话就使用指定的sessionid
if($_REQUEST['sessionid']){
    session_id($_REQUEST['sessionid']);
}
//动作的执行级别定义
define('ACT_OPEN',          0);		//不必登录,也无须验证权限
define('ACT_NEED_LOGIN',    1); 	//需要登录,但不用验证权限
define('ACT_NEED_AUTH',     2); 	//需要登录并验证权限
define('CONTENT_SKY',		10);	

//日志类型
define('L_NOTICE',		'NOTICE');		//消息
define('L_DEBUG',		'DEBUG');		//调试
define('L_WARNING',		'WARNING');		//警告
define('L_ERROR',		'ERROR');		//错误
define('L_DB',			'DATABASE');	        //数据库出错信息

define('BESPEAK_TYPE', serialize( array('1'=>'ACL(前房人工晶体)', '2'=>'EPI-LASIK(虹膜定位+波前像差)', '3'=>'标准LASIK(虹膜定位+波前像差)', '4'=>'标准LASEK(虹膜定位+波前像差)', '5'=>'超薄LASIK', '6'=>'EPI-LASIK', '7'=>'标准LASIK', '8'=>'标准LASEK')) );	// 手术类型
define('CLIENT_TYPE', serialize( array('1'=>'近视', '2'=>'白内障', '3'=>'眼底病', '4'=>'斜弱视', '5'=>'青光眼', '6'=>'医学验光配镜', '7'=>'眼干眼红眼涩', '8'=>'其它眼病')) );	// 咨询类型

//系统目录结构
define('ROOT',              realpath(dirname(__FILE__).'/..'));
define('APP_DIR',           ROOT.'/app');
define('SYS_DIR',           ROOT.'/sys');
define('WEB_DIR',           ROOT.'/www');
define('LIB_DIR',           SYS_DIR.'/lib');
define('VAR_DIR',           SYS_DIR.'/var');
define('DB_DIR',            SYS_DIR.'/db');

require_once 'errorhandler.php';
require_once 'global.php';
require_once 'core/Object.class.php';
require_once 'core/Core.class.php';
require_once 'core/Cache.class.php';
require_once 'core/Io.class.php';
require_once 'core/Config.class.php';
require_once 'core/Action.class.php';
require_once 'core/Session.class.php';
require_once 'core/Request.class.php';
require_once 'core/Language.class.php';
require_once 'core/Page.class.php';
require_once 'core/function.php';

//设置全局变量
$GLOBALS['fw_config'] = & Config::singleton();
$GLOBALS['fw_session'] = & Session::singleton();
$GLOBALS['fw_request'] = & Request::singleton();

define('URL',               $GLOBALS['fw_config']->get('site_url').str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']));
ini_set('date.timezone', $GLOBALS['fw_config']->get('timezone'));
if($GLOBALS['fw_config']->get('error_reporting')){
    error_reporting($GLOBALS['fw_config']->get('error_reporting'));
}
if($GLOBALS['fw_request']->get(array('reqdata'=>'lang')) && in_array($GLOBALS['fw_request']->get(array('reqdata'=>'lang')), $GLOBALS['fw_config']->get('language_support'))){
    $lang = $GLOBALS['fw_request']->get(array('reqdata'=>'lang'));
}else if($GLOBALS['fw_session']->get('language')){   
    $lang = $GLOBALS['fw_session']->get('language');
}else{ 
    $lang = $GLOBALS['fw_config']->get('language_default');
}
define('LANGUAGE', $lang);
unset($lang);
$GLOBALS['fw_session']->set('language', LANGUAGE);//保存当前会话的语言选择
$GLOBALS['language'] = & Language::singleton();
?>