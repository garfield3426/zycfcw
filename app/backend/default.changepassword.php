<?php

class ChangePassword extends Page{
    var $AuthLevel = ACT_NEED_LOGIN;
    var $db;
    var $userInfo;
    var $tab = 'sys_user';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
        $this->userInfo = $this->getData();

    }

    
    function process(){
        //检查是否提交数据
        if(!empty($this->input['submit'])){
            $this->submit();
            return;
        }
        //输出页面
        $this->export($this->userInfo);
    }

    
    function submit(){
        //获取提交的数据
        $item = array_merge(
            stripQuotes($this->userInfo),
            stripQuotes(trimArr($this->input['item']))
        );
        if(!$item) $item = $this->userInfo;
        //检查数据合法性
        $emsg = $this->validate($item);
        //如果数据不合法则输出
        if(count($emsg)){
            $this->export($item, $emsg);
            return;
        }
        //数据合法则更新数据
        $this->updateDb($item);
        Core::raiseMsg($this->lang->get('p_default_updateSucceedMsg'));
    }

    
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'jsonData' => array('emsg' => $emsg),
            'title' => $this->lang->get('p_default_changePasswordTitle'),
            'formAct' => Core::getUrl(),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }

    
    function validate($i){
        $e = array();
        if(!strlen($i['ouserpass'])){
            $e['ouserpass'] = $this->lang->get('e_default_ouserpassIsEmpty');
        }elseif(md5($i['ouserpass']) != $this->userInfo['userpass']){
            $e['ouserpass'] = $this->lang->get('e_default_ouserpassIsError');
        }
        if(!strlen($i['userpass'])){
            $e['userpass'] = $this->lang->get('e_default_userpassIsEmpty');
        }
        if(!strlen($i['cuserpass'])){
            $e['cuserpass'] = $this->lang->get('e_default_cuserpassIsEmpty');
        }
        if(strlen($i['userpass']) && strlen($i['cuserpass']) && $i['userpass'] != $i['cuserpass']){
            $e['userpass'] = $this->lang->get('e_default_userpassUnmatched');
        }
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }

    function getData(){
        $sql = "select * from {$this->tab} where id=".$this->sess->getUserId();
        $item = $this->db->GetRow($sql);
        return $item;
    }

    
    function updateDb($item){
        unset($item['id']);//防止ID号被修改
        unset($item['gid']);//防止组ID号被修改
        unset($item['state']);//防止状态被修改
        $item['userpass'] = md5($item['userpass']);
        $sql = "select * from {$this->tab} where id=".$this->sess->getUserId();
        $sql = $this->db->GetUpdateSQL($this->db->Execute($sql), $item);
        return $this->db->Execute($sql);
    }
}
?>
