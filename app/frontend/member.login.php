<?php
/*-----------------------------------------------------+
 * 登录操作
 * 
 * @author maorenqi 
 +-----------------------------------------------------*/
class Login extends Page{
    
    var $AuthLevel = ACT_OPEN;
    var $db;
    var $tab = 'member';
   // var $tab_group = 'sys_group';
    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
       // $this->tab_group = $this->conf->get('table_prefix').$this->tab_group;
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
		//数据合法则更新数据
        $this->updateDb($userInfo);
        //保存用户数据到Session
        $this->sess->setUserId($userInfo['id']);
        //$this->sess->setGroupId($userInfo['gid']);
        $this->sess->set('memberid', $userInfo['id']);
        $this->sess->set('membername', $userInfo['username']);
        //$this->sess->set('groupTitle', $this->getGroupTitle($userInfo['gid']));
        //清空尝试登录的次数
        $this->sess->set('login_times');
       
        //跳转页面
		if($userInfo['loginnum']==0){
			Core::redirect(Core::getUrl('profile','member',null,false,true));
		}else{
			Core::redirect(Core::getUrl('create','esfang',null,false,true));
		}
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

    
    function getUserInfo($username){
        $sql = "select * from {$this->tab} where username = '{$username}'";
        return $this->db->GetRow($sql);
    }

    
    /* function getGroupTitle($gid){
        return $this->db->GetOne("select title from {$this->tab_group} where id={$gid}");
    } */
	function updateDb($userInfo){
        unset($items['id']);//防止ID号被修改
       //vardump($item);exit;
        $sql = "select * from {$this->tab} where id={$userInfo['id']}";
		$items['lastdate'] = time();
        $items['lastip'] =  clientIp();
        $items['loginnum'] =  $userInfo['loginnum']+1;
        $sql = $this->db->GetUpdateSQL($this->db->Execute($sql), $items);
        return $this->db->Execute($sql);
    }
    
    function validate($item, $info){
        if(!strlen($item['username'])){
            PhpBox('用户名不能为空！');
    		GotoPage(h);
    		exit;
        }elseif(!$info['username']){
            PhpBox('用户名不存在！');
    		GotoPage(h);
    		exit;
        }
        if(!strlen($item['passwd'])){
            PhpBox('密码不能为空！');
    		GotoPage(h);
    		exit;
        }
        if(strlen($info['username']) && $info['state']!=1){
            PhpBox('该账户被锁，请联系管理员开通！');
    		GotoPage(h);
    		exit;
        }
        if(strlen($item['passwd']) && $info['passwd']!=md5($item['passwd'])){
            PhpBox('密码不对，请重新输入！');
    		GotoPage(h);
    		exit;
        }
    }
}
?>