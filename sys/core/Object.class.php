<?php
/**
 * Object ��
 * ������ PHP4,5 ����
 */
class Object {
    /**
     * ���췽��
     */
    function Object() {
        $args= func_get_args();
        register_shutdown_function(array (& $this, '__destruct'));
        call_user_func_array(array (& $this, '__construct'), $args);
    }

    /**
     * ���췽��
     */
    function __construct() {
    }

    /**
     * ��������
     */
    function __destruct() {
    }
}
?>
