<?php
/*-----------------------------------------------------+
 * 删除系統日志
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
class Delete extends Action{
    var $db;
    var $tab = 'log';
    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
    }
    
    function process(){
        $id = $this->input['id'];
        if($id=='all'){
            $sql = "delete from {$this->tab}";
        }else{
            if(is_array($id)){
                $idStr = implode(',',$id);
            }elseif(!is_numeric($id)){
                Core::raiseMsg($this->lang->get('e_systemLog_idIsEmpty'));
            }else{
                $idStr = $id;
            }
            $sql = "delete from {$this->tab} where id in({$idStr})";
        }
        $this->db->Execute($sql);
        Core::redirect(Core::getUrl('showlist','','',true));
    }
}
?>
