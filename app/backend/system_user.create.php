<?php
/*-----------------------------------------------------+
 * 新建系统用户
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
class Create extends Page{
    var $AuthLevel = ACT_NEED_LOGIN;
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
            Core::raiseMsg($this->lang->get('e_systemUser_invalidationManipulation'));
        }
        Core::redirect(Core::getUrl('showlist'));
    }

    
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_systemUser_createTitle'),
            'formAct' => Core::getUrl(),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->addTplFile('form');
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
                    '0' => $this->lang->get('p_systemUser_stateDisabled'),
                    '1' => $this->lang->get('p_systemUser_stateEnabled'),
                    //'2' => $this->lang->get('p_systemUser_stateDelete'),
                ),
            ),
            //会员组选择器
            'item[gid]' => array(
                'display' => true,
                'value' => $item['gid'],
                'list' => $this->getGroupList(),
            ),
            
            'goBackLink' =>array('url' => Core::getUrl('showlist','','',true)),
        );
    }

    
    function getGroupList(){
        $list = $this->db->GetAll("select id, title from {$this->tab_group}");
        $result = array();
        foreach($list as $i) $result[$i['id']] = $i['title'];
        return $result;
    }

    
    function insertDb($item){
        unset($item['id']);//防止ID号被修改
        $item['userpass'] = md5($item['userpass']);//对密码进行md5处理
        $item['reg_time'] = time();
        $sql = "select * from {$this->tab} where id=-1";
        $sql = $this->db->GetInsertSQL($this->db->Execute($sql), $item);
        return $this->db->Execute($sql);
    }

    
    function isExist($username){
        $sql = "select count(*) from {$this->tab} where username='{$username}'";
        return $this->db->GetOne($sql) ? true : false;
    }

    
    function validate($i){
        $e = array();
        if(!$i['username']){
            $e['username'] = $this->lang->get('e_systemUser_usernameIsEmpty');
        }elseif($this->isExist($i['username'])){
            $e['username'] = $this->lang->get('e_systemUser_usernameIsExist');
        }
        if(!is_numeric($i['state'])){
            $e['state'] = $this->lang->get('e_systemUser_stateIsEmpty');
        }
        if(!strlen($i['gid'])){
            $e['gid'] = $this->lang->get('e_systemUser_groupIsEmpty');
        }
        if(!strlen($i['email'])){
            $e['email'] = $this->lang->get('e_systemUser_emailIsEmpty');
        }
        if(!strlen($i['phone'])){
            $e['phone'] = $this->lang->get('e_systemUser_phoneIsEmpty');
        }
        if(!strlen($i['intro'])){
            $e['intro'] = $this->lang->get('e_systemUser_introIsEmpty');
        }
        if(!strlen($i['userpass'])){
            $e['userpass'] = $this->lang->get('e_systemUser_userpassIsEmpty');
        }
        if(!strlen($i['cuserpass'])){
            $e['cuserpass'] = $this->lang->get('e_systemUser_cuserpassIsEmpty');
        }
        if(!strlen($i['userpass'] && !strlen($i['cuserpass'])) && $i['userpass'] != $i['cuserpass']){
            $e['userpass'] = $this->lang->get('e_systemUser_userpassUnmatched');
        }
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
