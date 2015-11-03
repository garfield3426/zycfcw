<?php
/**
 * Object 类
 * 基础类 PHP4,5 兼容
 */
class Object {
    /**
     * 构造方法
     */
    function Object() {
        $args= func_get_args();
        register_shutdown_function(array (& $this, '__destruct'));
        call_user_func_array(array (& $this, '__construct'), $args);
    }

    /**
     * 构造方法
     */
    function __construct() {
    }

    /**
     * 析构方法
     */
    function __destruct() {
    }
}
?>
