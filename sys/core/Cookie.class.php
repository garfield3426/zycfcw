<?php 
/**
 * Cookie 类
 * 提供对cookie的基本操作
 */
class Cookie extends Object {
    var $prefix;
    var $expire;
    var $path;
    var $domain;

    /**
     * 构造方法
     */
    function __construct() {
        $this->prefix = $GLOBALS['config']->get('cookie_prefix');
        $this->expire = $GLOBALS['config']->get('cookie_expire');
        $this->path   = $GLOBALS['config']->get('cookie_path');
        $this->domain = $GLOBALS['config']->get('cookie_domain');
    }

    /**
     * 析构方法
     */
    function __destruct() {
        $this->end();
    }

    /**
     * 获得类一个唯一的实例
     * @return object
     */
    function & singleton() {
        static $instance;
        if (!isset ($instance)) {
            $class= __CLASS__;
            $instance= new $class();
        }
        return $instance;
    }

    /**
     * 获取Cookie的一个值
     * return mixd
     */
    function get($key) {
        return $_COOKIE[$this->prefix][$key];
    }
    /**
     * 设置Cookie的一个值
     * @param mixd $key
     * @param mixd $val
     * @param string $expire
     * @param string $path
     * @param string $domain
     */
    function set($key,$val=null) {
        if(empty($val)){
            setcookie($this->expire.'['.$key.']', "", time() - 3600);
            unset($_COOKIE[$this->prefix][$key]);
            return;
        }
        setcookie($this->prefix.'['.$key.']', $val,time()+$this->expire,$this->path,$this->domain);
        $_COOKIE[$this->prefix][$key] = $value;
    }

    /**
     * 清空Cookie值
     */
    function end(){
        unset($_COOKIE[$GLOBALS['config']->get('cookie_prefix')]);
    }
}
?>
