<?php
/**
 * Action 类
 * 动作处理
 */
class Action extends Object {
    var $AuthLevel= ACT_NEED_AUTH;
    //var $AuthLevel= ACT_NEED_LOGIN;
    var $conf;
    var $sess;
    var $lang;
    var $input;

    /**
     * 构造方法
     */
    function __construct() {
        $this->conf =& Config::singleton();
        $this->sess =& Session::singleton();
        $this->lang =& Language::singleton();
        $this->input =& $GLOBALS['fw_request']->get('reqdata');
    }

    /**
     * 析构方法
     */
    function __destruct() {
    }

    /**
     * 执行入口
     */
    function process() {
    }
}
?>
