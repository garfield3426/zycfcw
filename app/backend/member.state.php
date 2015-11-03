<?php
/*-----------------------------------------------------+
 * 改变用户状态
 *
 * value = 0 未发布
 * value = 1 已发布
 * value = 2 已删除(非实际删除)
 *
 * @author Try.Shieh@gamil.com 
 +-----------------------------------------------------*/
class State extends Action{
    var $db;
    var $tab = 'member';
    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
    }
    
    function process(){
        $id = $this->input['id'];
        $val = (int)$this->input['value'];
        if(0 > $val || 2 < $val){
            Core::raiseMsg($this->lang->get('e_member_ValueIsImproper'));
        }
        if(is_array($id)){
            $idStr = implode(',',$id);
        }elseif(!is_numeric($id)){
            Core::raiseMsg($this->lang->get('e_member_idIsEmpty'));
        }else{
            $idStr = $id;
        }
        if(2 == $val) Core::log(L_NOTICE, sprintf($this->lang->get('log_member_delete'), $idStr));
        $sql = "update {$this->tab} set state={$val} where id in({$idStr})";
        $this->db->Execute($sql);
        Core::redirect(Core::getUrl('showlist','','',true));
    }
}
?>
