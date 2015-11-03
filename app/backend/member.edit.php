<?php
/*-----------------------------------------------------+
 * 修改会员资料
 *
 * @author Try.Shieh@gamil.com 
 +-----------------------------------------------------*/
class Edit extends Page{
    var $db;
    var $currentId;
    var $tab = 'member';
    //var $tab_group = 'mb_group';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
      //  $this->tab_group = $this->conf->get('table_prefix').$this->tab_group;
        $this->db = getDb();
        //$this->db->debug = true;
    }

    
    function process(){
        //获取当前的操作id
        $this->currentId = is_numeric($this->input['id'])
            ? $this->input['id']
            : $this->input['item']['id'];
        //没有则返回错误信息
        if(!is_numeric($this->currentId)){
            Core::raiseMsg($this->lang->get('e_member_idIsEmpty'));
        }
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
        //检查数据合法性
        $emsg = $this->validate($item);
        //如果数据不合法则输出
        if(count($emsg)){
            $this->export($item, $emsg);
            return;
        }
        //数据合法则更新数据
        $this->updateDb($item);
        //返回列表页
        Core::redirect(Core::getUrl('showlist','','',true));
    }

    
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_member_editTitle'),
            'formAct' => Core::getUrl(),
            'passHint' => $this->lang->get('p_member_userpassHint'),
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
                'value' => $item['state'],
                'list' => array(
                    '0' => $this->lang->get('p_member_stateDisabled'),
                    '1' => $this->lang->get('p_member_stateEnabled'),
                    //'2' => $this->lang->get('p_member_stateDelete'),
                ),
            ),
            //地区选择器
            'item[area]' => array(
                'value' => $item['area'],
                'list' => $this->lang->get('global_areaList'),
            ),
            //会员组选择器
            /* 'item[gid]' => array(
                'display' => true,
                'value' => $item['gid'],
                'list' => $this->getGroupList(),
            ), */
            'goBackLink' =>array('url' => Core::getUrl('showlist','','',true)),
        );
    }

    
    function getGroupList(){
        $list = $this->db->GetAll("select id, title from {$this->tab_group}");
        $result = array();
        foreach($list as $i) $result[$i['id']] = $i['title'];
        return $result;
    }

    
    function getData(){
        $sql = "select * from {$this->tab} where id={$this->currentId}";
        $item = $this->db->GetRow($sql);
        $item['reg_time'] = date('Y-m-d', $item['reg_time']);
        return $item;
    }

    
    function updateDb($item){
        unset($item['id']);//防止ID号被修改
        if(!strlen($item['userpass'])){
            //防止清空密码
            unset($item['userpass']);
        }else{
            //对密码进行md5处理
            $item['userpass'] = md5($item['userpass']);
        }
        $sql = "select * from {$this->tab} where id={$this->currentId}";
        $sql = $this->db->GetUpdateSQL($this->db->Execute($sql), $item);
        return $this->db->Execute($sql);
    }

    
    function isExist($username){
        $sql = "select count(*) from {$this->tab} where username='{$username}' and id<>{$this->currentId}";
        return $this->db->GetOne($sql) ? true : false;
    }

    
    function validate($i){
        $e = array();
        if(!$i['username']){
            $e['username'] = $this->lang->get('e_member_usernameIsEmpty');
        }elseif($this->isExist($i['username'])){
            $e['username'] = $this->lang->get('e_member_usernameIsExist');
        }
        if(!is_numeric($i['state'])){
            $e['state'] = $this->lang->get('e_member_stateIsEmpty');
        }
        if(!strlen($i['gid'])){
            $e['gid'] = $this->lang->get('e_member_groupIsEmpty');
        }
        if(!strlen($i['email'])){
            $e['email'] = $this->lang->get('e_member_emailIsEmpty');
        }
        
        if(strlen($i['userpass']) && $i['userpass'] != $i['cuserpass']){
            $e['userpass'] = $this->lang->get('e_member_userpassUnmatched');
        }
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
