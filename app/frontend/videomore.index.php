<?php

class Index extends Page{
    var $AuthLevel = ACT_OPEN;
    var $db;
    var $tab="video";
    
    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
       
    }
    
    function process(){
    	
    	$pvar = array(
    		//search form Action
            'formAct' => Core::getUrl('showlist', 'article', array('page'=>0), true,true),  
            //手术类型
            'bespeak_type' => unserialize(CLIENT_TYPE),           
        );
       
        //设定模板使用变量
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }
   
}
?>
