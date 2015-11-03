<?php
/*
 * 提供从语言文件获取信息的基本操作
 *
 * @package Core
 * @author Try.Shieh@gmail.com
 */
class Language extends Object{ 
    var $lang = array();

    /**
     * 构造方法
     */
    function __construct($appId, $lang){
        //判读是否支持该语言
        if(!in_array($lang, $GLOBALS['fw_config']->get('language_support'))){
            //如果不支持则使用默认语言
            $lang = $GLOBALS['fw_config']->get('language_default');
        }
        $appLangFile = APP_DIR."/$appId/!language.$lang.php";
        //如果没有相应的语言文件则触发异常
        if(!file_exists($appLangFile)){
            trigger_error(_Error(0x030101, array(__FUNCTION__, $appLangFile)));
        }
        $this->lang = require_once $appLangFile;
    }

     /**
     * 获得一个唯一的实例
     * @return object
     */
    function & singleton(){
        static $instance;
        if(!isset($instance)){
            $class= __CLASS__;
            $instance= new $class(APP_ID, LANGUAGE);
        }
        return $instance;
    }

    /**
     * 获取当前语言文件的一个值
     * @param string $key[,$key...]
     * @return string
     */
    function get(){
        $args = func_get_args();
        $lang = $this->lang;
        foreach($args as $v){
            $lang = $lang[$v] ? $lang[$v] : '';
        }
        return $lang;
    }

    /**
     * 获取当前语言选择器内容列表
     * @return array
     */
    function getSelectList(){
        $list = array();
        foreach($GLOBALS['fw_config']->get('language_support') as $i){
            $list[$i] = $this->get('global_language',$i);
        }
        return $list;
    }
}
?>
