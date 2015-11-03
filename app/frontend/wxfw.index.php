<?php
class Index extends Page{
    var $AuthLevel = ACT_OPEN;
    var $db;
    var $tab="bespeak";
    
    
    function __construct(){
        parent::__construct();

        $this->tab = $this->conf->get('table_prefix').$this->tab;

        $this->db = getDb();
    }
    
    function process(){
    	
    	$pvar = array(
    		//'total' => $this->getBespeak(),
    		//search form Action
            'formAct' => Core::getUrl('showlist', 'article', array('page'=>0), true,true),  
           
        );
       
        //设定模板使用变量
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }
   
}
?>