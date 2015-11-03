<?php
/**
 * Session 类
 * 提供对Session的基本操作和部分扩展操作
 */
class Session extends Object {
    var $sessionPath;
    /**
     * 构造方法
     */
    function __construct() {
        $this->sessionPath = VAR_DIR.'/session/'.SESSION_PATH;
        if(!file_exists($this->sessionPath)){
            Io::mkdir($this->sessionPath.'/.');
        }
        session_save_path($this->sessionPath);
        session_cache_limiter('private, must-revalidate');
        session_start();
        if (!isset($_SESSION['access_time'])) {
            $_SESSION['access_time']= time();
        }
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
     * 获得用户当前的会员组ID
     * @return int
     */
    function getGroupId() {
        return (int)$_SESSION['user']['gid'];
    }

    /**
     * 设置用户当前的会员组ID
     * @param int $id
     */
    function setGroupId($id) {
        $_SESSION['user']['gid']= (int)$id;
    }

    /**
     * 获得用户当前的会员ID
     * @return int
     */
    function getUserId() {
        return (int)$_SESSION['user']['uid'];
    }

    /**
     * 设置用户当前的会员ID
     * @param int $id
     */
    function setUserId($id) {
        $_SESSION['login_time']= time();
        $_SESSION['user']['uid']= (int)$id;
    }

    /**
     * TODO:补充注释
     */
    function setQueryData($data) {
        $_SESSION['query_data']= $data;
    }

    /**
     * 获得登录后跳转的URL
     * @return string
     */
    function getNextTo() {
        return $_SESSION['login_to_here'];
    }
    /**
     * 设置登录后跳转的URL
     * @param string $url
     */
    function setNextTo($url) {
        $_SESSION['login_to_here']= $url;
    }

    /**
     * 更新最后一次活动的时间
     */
    function updateLastActTime() {
        $_SESSION['user']['last_action_time']= time();
    }

    /**
     * 取得最后一次活动的时间
     * @return int
     */
    function getLastActTime() {
        return $_SESSION['user']['last_action_time'];
    }

    /**
     * 取得一个session变量值
     * @param string $key
     * @return mixd
     */
    function & get($key) {
        return $_SESSION['data'][$key];
    }

    /**
     * 设置和清除一个session变量
     * 如果没有指定$val值将会把session中的$key变量清除
     * @param string $key 键名
     * @param mixd $var 值
     */
    function set($key, $val= null) {
        if (empty ($val)) {
            unset ($_SESSION['data'][$key]);
            return;
        }
        $_SESSION['data'][$key]= $val;
    }

    /**
     * 结束Session
     */
    function end() {
        unset($_SESSION);
        session_destroy();
    }

    /**
     * 清理过期Session
     */
    function gc(){
        if(!is_dir($this->sessionPath)){
            return false;
        }
        if(!$dh = opendir($this->sessionPath)){
            return false;
        }
        clearstatcache();
        while(($f = readdir($dh)) !== false){
            $f = $this->sessionPath.'/'.$f;
            if(is_dir($f)) continue;

            //将最后修改时间超过一天的Session删除
            if(filemtime($f) + 24 * 3600 < time()){
                unlink($f);
            }
        }
        closedir($dh);
    }
}
?>
