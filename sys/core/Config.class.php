<?php
/**
 * Config 类
 * 提供对配置文件的读取写入操作
 */
class Config extends Object {
    var $conf = array();

    /**
     * 构造方法
     */
    function __construct() {
        if(file_exists(VAR_DIR."/config.cache")){
            $this->conf = arrayMerge(
                unserialize(file_get_contents(VAR_DIR."/config.cache")),
                require_once(APP_DIR."/".APP_ID."/!config.php")
            );
        }else{
            $this->conf = file_exists(APP_DIR."/".APP_ID."/!config.php")
                ? arrayMerge(
                    require_once(SYS_DIR.'/default.config.php'),
                    require_once(APP_DIR."/".APP_ID."/!config.php")
                )
                : require_once(SYS_DIR.'/default.config.php');
        }
    }


    /**
     * 获得类一个唯一的实例
     * @return object
     */
    function & singleton() {
        static $instance;
        if (!isset($instance)) {
            $class= __CLASS__;
            $instance= new $class();
        }
        return $instance;
    }

    /**
     * 获得一个设置值
     * @param mixd $key[,$key...]
     * @return mixd
     */
    function get() {
        $args = func_get_args();
        $conf = $this->conf;
        foreach($args as $v){
            $conf = $conf[$v];
        }
        return $conf;
    }

    /**
     * 设置一个设置值并返回
     * @param mixd $key
     * @param mixd $val
     * @return mixd
     */
    function set($key, $val){
        return $this->conf[$key] = $val;
    }

    /**
     * 更新设置项
     * @param array $item
     * @return array
     */
    function update($item){
        return $this->conf = arrayMerge($this->conf, $item);
    }

    /**
     * 保存设置
     */
    function save(){
        IO::writeFile(VAR_DIR."/config.cache", serialize($this->conf));
        return;
    }
}
?>
