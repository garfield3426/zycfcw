<?php
/*-----------------------------------------------------+
 * 登录操作
 * 
 * @author maorenqi 
 +-----------------------------------------------------*/
class Login extends Page{
    
    var $AuthLevel = ACT_OPEN;
    var $db;
    var $tab = 'sys_user';
    var $tab_group = 'sys_group';
    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_group = $this->conf->get('table_prefix').$this->tab_group;
        $this->db = getDb();
        //$this->db->debug = true;
    }
    
    function process(){
        //检查是否提交数据
        if(!empty($this->input['submit'])){
            $this->submit();
            return;
        }
        $this->sess->gc();
        //输出页面
        $this->export();
    }

    
    function submit(){ 
        //获取提交的数据
        $item = stripQuotes(trimArr($this->input['item']));
        //获取用户数据
        $userInfo = $this->getUserInfo($item['username']);
        //检查数据合法性
        $emsg = $this->validate($item, $userInfo);
        //如果数据不合法则输出
        if(count($emsg)){
            $this->export($item, $emsg);
            return;
        }
        //保存用户数据到Session
        $this->sess->setUserId($userInfo['id']);
        $this->sess->setGroupId($userInfo['gid']);
        $this->sess->set('username', $userInfo['username']);
        $this->sess->set('groupTitle', $this->getGroupTitle($userInfo['gid']));
        //清空尝试登录的次数
        $this->sess->set('login_times');
       
        //跳转页面
        Core::redirect(Core::getUrl('setting','system'));
        /*Core::redirect(Core::getUrl(
            $this->conf->get('action_default'),
            $this->conf->get('module_default')
        ));*/
    }

    //模板要调用的数据的处理
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_login_title'),
            'formAct' => Core::getUrl(),
        );
        //$this->assign('v', stripQuotes($pvar));
        $this->assign('v', $pvar);
        $this->display();
    }

    
    function getJsonData($item, $emsg=null){
        return array('emsg' => $emsg,);
    }

    
    function getUserInfo($usernmae){
        $sql = "select * from {$this->tab} where username = '{$usernmae}'";
        return $this->db->GetRow($sql);
    }

    
    function getGroupTitle($gid){
        return $this->db->GetOne("select title from {$this->tab_group} where id={$gid}");
    }

    
    function validate($item, $info){
        $e = array();
        if(!strlen($item['username'])){
            $e['username'] = $this->lang->get('e_login_usernameIsEmpty');
        }elseif(!$info['username']){
            $e['username'] = $this->lang->get('e_login_usernameIsNotExist');
        }
        if(!strlen($item['userpass'])){
            $e['userpass'] = $this->lang->get('e_login_userpassIsEmpty');
        }
        if(strlen($info['username']) && $info['state']!=1){
            $e['username'] = $this->lang->get('e_login_stateIsDeleteOrLock');
        }
        if(strlen($item['userpass']) && $info['userpass']!=md5($item['userpass'])){
            $e['userpass'] = $this->lang->get('e_login_userpassIncorrectness');
        }
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
