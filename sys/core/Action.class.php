<?php
/**
 * Action ��
 * ��������
 */
class Action extends Object {
    var $AuthLevel= ACT_NEED_AUTH;
    //var $AuthLevel= ACT_NEED_LOGIN;
    var $conf;
    var $sess;
    var $lang;
    var $input;

    /**
     * ���췽��
     */
    function __construct() {
        $this->conf =& Config::singleton();
        $this->sess =& Session::singleton();
        $this->lang =& Language::singleton();
        $this->input =& $GLOBALS['fw_request']->get('reqdata');
    }

    /**
     * ��������
     */
    function __destruct() {
    }

    /**
     * ִ�����
     */
    function process() {
    }
}
?>
