<?php
/*-----------------------------------------------------+
 * 改变相册状态
 *
 * value = 0 未发布
 * value = 1 已发布
 * value = 2 已删除(非实际删除)
 *
 * @author maorenqi
 +-----------------------------------------------------*/
class State extends Action{
    var $db;
    var $tab = 'album';
    var $tab_photo = 'album_photo';
    /**
     * 构造方法
     */
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_photo = $this->conf->get('table_prefix').$this->tab_photo;
        $this->db = getDb();
        //$this->db->debug = true;
    }
    /**
     * 程序入口
     */
    function process(){
        $id = $this->input['id'];
        $val = (int)$this->input['value'];
        if(0 > $val || 2 < $val){
            Core::raiseMsg($this->lang->get('e_link_ValueIsImproper'));
        }
        if(is_array($id)){
            $idStr = implode(',',$id);
        }elseif(!is_numeric($id)){
            Core::raiseMsg($this->lang->get('e_link_idIsEmpty'));
        }else{
            $idStr = $id;
        }
        if(2!=$val){
            $sql = "update {$this->tab} set state={$val} where id in({$idStr})";
            $this->db->Execute($sql);
        }else{
            $sql = "DELETE FROM {$this->tab} WHERE id in ({$idStr})";
            $this->db->Execute($sql);
            $sql = "UPDATE $this->tab_photo SET state=2 WHERE album_id IN ({$idStr})";
            $this->db->Execute($sql);
            Core::log(L_NOTICE, sprintf($this->lang->get('log_album_delete'), $idStr));
        }
        Core::redirect(Core::getUrl('showlist','','',true));
    }
}
?>
