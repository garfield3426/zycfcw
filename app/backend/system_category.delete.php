<?php
/*-----------------------------------------------------+
 * 删除系統日志
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
class Delete extends Action{
    var $db;
    var $tab = 'category';
    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
    }
    
    function process(){
        $id = $this->input['id'];
        if(!is_numeric($id)){
            Core::raiseMsg($this->lang->get('e_systemCategory_idIsEmpty'));
        }
        if(!class_exists('Treenode')) include_once(LIB_DIR.'/treenode.class.php');
        $node = new Treenode($this->tab, $this->db);
        $node->delNode($id);
        $node->buildTree(1,1);
        IO::removeFile(VAR_DIR.'/category/');		//清除缓存
        Core::redirect(Core::getUrl('showlist','','',true));
    }
}
?>
