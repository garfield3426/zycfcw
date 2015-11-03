<?php

class Profile extends Page{
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
        $this->export(stripQuotes($this->getData()));
    }

    
    function submit(){
        //获取提交的数据
        $item = stripQuotes(trimArr($this->input['item']));
        if(!$item) $item = $this->getData();
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
            'title' => $this->lang->get('p_default_profileTitle'),
            'formAct' => Core::getUrl(),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }

    
    function validate($i){
        $e = array();
        if($i['email'] && !ext('is_email', $i['email']) ){
            $e['email'] = $this->lang->get('e_default_emailError');
        }
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }

    function getData(){
        $sql = "select u.*, g.title as gid from {$this->tab} u left join {$this->tab_group} g on u.gid = g.id where u.id=".$this->sess->getUserId();
        $item = $this->db->GetRow($sql);
        $item['reg_date'] = date('Y-m-d', $item['reg_date']);
        $item['state'] = $item['state']
            ? $this->lang->get('p_default_stateEnable')
            : $this->lang->get('p_default_stateDisable');
        return $item;
    }

    
    function updateDb($item){
        unset($item['id']);//防止ID号被修改
        unset($item['gid']);//防止组ID号被修改
        unset($item['state']);//防止状态被修改
        $sql = "select * from {$this->tab} where id=".$this->sess->getUserId();
        $sql = $this->db->GetUpdateSQL($this->db->Execute($sql), $item);
        return $this->db->Execute($sql);
    }
}
?>
