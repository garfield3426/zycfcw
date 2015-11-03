<?php
/*-----------------------------------------------------+
 * 对视频排序
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
//include_once(LIB_DIR.'/category.class.php');
class Order extends Page{
    var $db;
    var $tab = 'video';
    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
    }
    
    function process(){
    	if($this->input['toorder']){
			$id = $this->input['toid'];
	        $val = (int)$this->input['toorder'];
	        $sql = "update {$this->tab} set seq={$val} where id={$id}";
	        $this->db->Execute($sql);
	        //Core::redirect(Core::getUrl('showlist','','',true));
		}
    }
}
?>
