<?php
/*-----------------------------------------------------+
 * 是否同意
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
//include_once(LIB_DIR.'/category.class.php');
class Agreest extends Page{
    var $db;
    var $tab = 'notice';
    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        $this->db->debug = true;
    }
    
    function process(){
    	
    	if($this->input['yorn']){
			$id = $this->input['toid'];
	        $val = $this->input['yorn'];
	        $sql = "update {$this->tab} set leave_yorn={$val} where id={$id}";
	        $this->db->Execute($sql);
		}
    }
}
?>
