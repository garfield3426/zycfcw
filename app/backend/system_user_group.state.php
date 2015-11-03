<?php
/*-----------------------------------------------------+
 * 改变用户组状态
 *
 * value = 0 禁用
 * value = 1 正常
 * value = 2 已删除(非实际删除)
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
class State extends Action{
    var $AuthLevel = ACT_NEED_LOGIN;
    var $db;
    var $tab = 'sys_group';
    var $tab_priv = 'sys_group_priv';
    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_priv = $this->conf->get('table_prefix').$this->tab_priv;
        $this->db = getDb();
        //$this->db->debug = true;
    }
    
    function process(){
        $id = $this->input['id'];
        $val = (int)$this->input['value'];
        if(0 > $val || 2 < $val){
            Core::raiseMsg($this->lang->get('e_systemUserGroup_valueisimproper'));
        }
        if(is_array($id)){
            $idStr = implode(',',$id);
        }elseif(!is_numeric($id)){
            Core::raiseMsg($this->lang->get('e_systemUserGroup_idisempty'));
        }else{
            $idStr = $id;
        }
        //改变用户组状态
        $this->db->Execute("update {$this->tab} set state={$val} where id in({$idStr})");
        switch($val){
        case 0:
            $this->removePermissionsFile($id);
            break;
        case 2:
            $this->db->Execute("delete from {$this->tab_priv} where group_id in({$idStr})");
            $this->removePermissionsFile($id);
            Core::log(L_NOTICE, sprintf($this->lang->get('log_systemUserGroup_delete'), $idStr));
            break;
        }
        Core::redirect(Core::getUrl('showlist','','',true));
    }

    
    function removePermissionsFile($id){
        if(!is_array($id)) $id = array($id);
        foreach($id as $v){
            IO::removeFile(VAR_DIR."/group_priv/".APP_ID."/{$v}");
        }
    }
}
?>
