<?php

define('APP_ID', 	'frontend');
define('SESSION_PATH', 'frontend');
include_once '../../sys/boot.php';

require(LIB_DIR.'/ucenter_adapter/api/ucenter.inc.php');

if(in_array($get['action'], array('test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcreditsettings', 'updatecreditsettings'))) {
    $note = new UC_note();
    exit($note->$get['action']($get,$post));
}else{
    exit(API_RETURN_FAILED);
}


class UC_note{
    
    var $db;
    var $tab = 'member';
    var $tab_group = 'mb_group';
    var $sess;
    
    function __construct(){
        $this->conf = $GLOBALS['fw_config'];
        $this->sess = $GLOBALS['fw_session'];
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_group = $this->conf->get('table_prefix').$this->tab_group;
        $this->db = getDb();
    }
    
    function test($get,$post){
        return API_RETURN_SUCCEED;
    }
    
    function synlogin($get,$post){
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
        //获取会员组数据
		$username = $get['username'];
        $userInfo = $this->db->GetRow("select * from {$this->tab} where username = '{$username}'");
        $groupInfo = $this->db->GetRow("select * from {$this->tab_group} where id={$userInfo['gid']}");
        //保存用户数据到Session
        $this->sess->setUserId($userInfo['id']);
        $this->sess->setGroupId($userInfo['gid']);
        $this->sess->set('username', $userInfo['username']);
        $this->sess->set('groupTitle', $groupInfo['title']);
        $this->sess->set('discount', $groupInfo['discount']);
        //清空尝试登录的次数
        $this->sess->set('login_times');
    }

	function synlogout($get, $post) {
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		//获取会员组数据
		$this->sess->end();
	}

	function deleteuser($get, $post) {
		return API_RETURN_FAILED;  //暂时不能从ucenter删除emba的用户
	}

	function renameuser($get, $post) {
	    //TODO 临时处理
		return API_RETURN_SUCCEED;
	}

	function gettag($get, $post) {
		return;
	}

	function updatepw($get, $post) {
	    $username = $get['username'];
        $password = md5($get['password']);
	    $this->db->Execute("UPDATE {$this->tab} SET userpass='$password' WHERE username='$username'");
		return API_RETURN_SUCCEED;
	}

	function updatebadwords($get, $post) {
		return API_RETURN_SUCCEED;
	}

	function updatehosts($get, $post) {
		return API_RETURN_SUCCEED;
	}

	function updateapps($get, $post) {
		return API_RETURN_SUCCEED;
	}

	function updateclient($get, $post) {
		return API_RETURN_SUCCEED;
	}

	function updatecredit($get, $post) {
		return API_RETURN_SUCCEED;
	}

	function getcredit($get, $post) {
	    return;
	}

	function getcreditsettings($get, $post) {
	    return;
	}

	function updatecreditsettings($get, $post) {
		return API_RETURN_SUCCEED;
	}
    
}

?>
