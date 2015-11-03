<?php
/*-----------------------------------------------------+
 * 新建会员
 *
 * @author garfield
 +-----------------------------------------------------*/
class Register extends Page{
	var $AuthLevel = ACT_OPEN;
    var $db;
    var $tab = 'member';
    //var $tab_group = 'member_group';

    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        //$this->tab_group = $this->conf->get('table_prefix').$this->tab_group;
        $this->db = getDb();
        //$this->db->debug = true;
    }

    
    function process(){
        //检查是否提交数据
        if(!empty($this->input['submit'])){
            $this->submit();
            return;
        }
        //输出页面
        $this->export();
    }

    
    function submit(){
        //获取提交的数据
        $item = stripQuotes(trimArr($this->input['item']));
        //检查数据合法性
        $emsg = $this->validate($item);
        //如果数据不合法则输出
        if(count($emsg)){
            $this->export($item, $emsg);
            return;
        }
        //数据合法则写入数据
        if(!$this->insertDb($item)){
            //写入失败则输出信息
            Core::raiseMsg($this->lang->get('e_member_invalidationManipulation'));
        }else{
			PhpBox('注册成功!');
    		GotoPage(h);
    		exit;
		}
        Core::redirect(Core::getUrl('showlist'));
    }

    
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            //'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_member_createTitle'),
            'formAct' => Core::getUrl(),
        );
        $this->assign('v', stripQuotes($pvar));
        //$this->addTplFile('form');
        $this->display();
    }

    
    function getJsonData($item, $emsg=null){
        return array(
            'emsg' => $emsg,
            //状态选择器
            'item[state]' => array(
                'display' => true,
                'value' => $item['state'],
                'list' => array(
                    '0' => $this->lang->get('p_member_stateDisabled'),
                    '1' => $this->lang->get('p_member_stateEnabled'),
                    //'2' => $this->lang->get('p_member_stateDelete'),
                ),
            ),
            /* //地区选择器
            'item[area]' => array(
                'value' => $item['area'],
                'list' => $this->lang->get('global_areaList'),
            ),
            //会员组选择器
            'item[gid]' => array(
                'display' => true,
                'value' => $item['gid'],
                'list' => $this->getGroupList(),
            ), */
            'goBackLink' =>array('url' => Core::getUrl('showlist','','',true)),
        );
    }

    
   /*  function getGroupList(){
        $list = $this->db->GetAll("select id, title from {$this->tab_group}");
        $result = array();
        foreach($list as $i) $result[$i['id']] = $i['title'];
        return $result;
    } */

    
    function insertDb($item){
        unset($item['userid']);//防止ID号被修改
        $item['passwd'] = md5($item['passwd']);//对密码进行md5处理
        $item['regdate'] = time();
        $item['state'] = 1;
	
        $sql = "select * from {$this->tab} where id=-1";
        $sql = $this->db->GetInsertSQL($this->db->Execute($sql), $item);
        return $this->db->Execute($sql);
    }

    
    function isExist($username){
        $sql = "select count(*) from {$this->tab} where username='{$username}'";
        return $this->db->GetOne($sql) ? true : false;
    }

    
    function validate($i){
		if($i['code']!=$this->sess->get('code')){
    		PhpBox('验证码错误!');
    		GotoPage(h);
    		exit;
    	}
        if(!$i['username']){
			PhpBox('用户名不能为空！');
    		GotoPage(h);
    		exit;
        }elseif($this->isExist($i['username'])){
			PhpBox('该用户名已被注册，请输入其它用户名！');
    		GotoPage(h);
    		exit;
        }
     
        if(!strlen($i['passwd'])){
			PhpBox('密码不能为空！');
    		GotoPage(h);
    		exit;
        }
        if(!strlen($i['cpasswd'])){
			PhpBox('确认密码不能为空');
    		GotoPage(h);
    		exit;
        }
        if(!strlen($i['passwd'] && !strlen($i['cpasswd'])) && $i['passwd'] != $i['cpasswd']){
			PhpBox('密码和确认密码不一样，请重新输入！');
    		GotoPage(h);
    		exit;
        }
    }
}
?>
