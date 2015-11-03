<?php
/*-----------------------------------------------------+
 * 改变相片状态
 *
 * value = 0 未发布
 * value = 1 已发布
 * value = 2 已删除(非实际删除)
 *
 * @author maorenqi
 +-----------------------------------------------------*/
class Info extends Action{
    var $db;
    var $tab = 'album_photo';
    //var $tab_album = 'album';
    /**
     * 构造方法
     */
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        //$this->tab_album = $this->conf->get('table_prefix').$this->tab_album;
        $this->db = getDb();
        //$this->db->debug = true;
    }
    /**
     * 程序入口
     */
    function process(){
        $id = $this->input['photo_id'];
        $val = $this->input['info'];
        $sql = "update {$this->tab} set info='{$val}' where id ={$id}";
        $result = $this->db->Execute($sql);
        if($result){
        	phpBox('内容更新成功！');
        	gotoPage(h);
        	exit;
        }else{
        	phpBox('内容更新失败！');
        	gotoPage(h);
        	exit;
        }
        
    }
}
?>
