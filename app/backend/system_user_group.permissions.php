<?php
/*-----------------------------------------------------+
 * 修改用户组权限
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
class Permissions extends Page{
    var $AuthLevel = ACT_NEED_LOGIN;
    var $db;
    var $currentId;
    var $tab = 'sys_group_priv';
    var $tab_permissions = 'sys_permissions';

    
    function __construct(){
        parent::__construct();
        $this->tab_group = $this->conf->get('table_prefix').$this->tab_group;
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_permissions = $this->conf->get('table_prefix').$this->tab_permissions;
        $this->db = getDb();
        //$this->db->debug = true;
    }

    
    function process(){
        //获取当前的操作id
        $this->currentId = is_numeric($this->input['id']) ? $this->input['id'] : $this->input['group_id'];
        //没有则返回错误信息
        if(!is_numeric($this->currentId)){
            Core::raiseMsg($this->lang->get('e_systemUserGroup_idIsEmpty'));
        }
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
        $perms = stripQuotes(trimArr($this->input['permissions']));
        $this->updateDb($perms);
        //返回权限设置
        Core::redirect(Core::getUrl('showlist','','',true));
    }

    
    function export(){
        //页面输出的数据
        $pvar = array(
            'group_id' => $this->currentId,
            'jsonData' => $this->getJsonData(),
            'list' => $this->getSystemPermissions(),
            'title' => $this->lang->get('p_systemUserGroup_permissionsTitle'),
            'formAct' => Core::getUrl(),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }

    
    function getJsonData(){
        return array(
            'permissions' => $this->getGroupPermissions(),
            'goBackLink' => array('url' => Core::getUrl('showlist','','',true)),
        );
    }

    
    function getGroupPermissions(){
        $list = array();
        $sql= "select gp.*, p.id as id from {$this->tab} gp left join {$this->tab_permissions} p on gp.action_id=p.id where gp.group_id={$this->currentId}";
        $rs= $this->db->Execute($sql);
        while (!$rs->EOF) {
            $list[$rs->fields['id']]=$rs->fields['id'];
            $rs->MoveNext();
        }
        return $list;
    }

    
    function getSystemPermissions(){
        return $this->db->GetAll("select * from {$this->tab_permissions}");
    }

    
    function updateDb($perms){
        //重建权限数据库
        $this->db->Execute("delete from {$this->tab} where group_id={$this->currentId}");
        $sqlInsert = "insert into {$this->tab} (group_id, action_id) values";
        if($perms){
            foreach($perms as $val){
                $this->db->Execute("{$sqlInsert} ({$this->currentId}, {$val});");
            }
        }
        //重建权限缓存
        $sql = "select p.title as title from {$this->tab} gp left join {$this->tab_permissions} p on gp.action_id=p.id where gp.group_id={$this->currentId}";
        $rs = $this->db->Execute($sql);
        $data = array();
        while(!$rs->EOF){
            $data[] = $rs->fields['title'];
            $rs->MoveNext();
        }
        IO::writeFile(VAR_DIR."/group_priv/".APP_ID."/{$this->currentId}", serialize($data));
    }
}
?>
