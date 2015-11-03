<?php

class Index extends Page{
    var $AuthLevel = ACT_OPEN;
    var $db;
    var $tab="doctor";
    var $tab_article="article";
    
    
    function __construct(){
        parent::__construct();

        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_article = $this->conf->get('table_prefix').$this->tab_article;

        $this->db = getDb();
    }
    
    function process(){
    	
    	$pvar = array(
    		//'total' => $this->getBespeak(),
    		//search form Action
    		'doc1' => $this->getDoctor(0),
    		'doc2' => $this->getDoctor(1),
    		'doc3' => $this->getDoctor(2),
    		'doc4' => $this->getDoctor(3),
    		'aca1' => $this->getAca(9),
    		'aca2' => $this->getAca(12),
    		'aca3' => $this->getAca(16),
    		'aca4' => $this->getAca(20),
            'formAct' => Core::getUrl('showlist', 'article', array('page'=>0), true,true),  
           
        );
       
        //设定模板使用变量
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }
    
   function getDoctor($num){
   		$sql = "select id,name,img from {$this->tab} where state=1 order by is_order desc,id desc limit $num,1";
   		$result = $this->db->getAll($sql);
   		return $result;   		
   }
   
   function getAca($num){
   		$sql = "select id,title from {$this->tab_article} where state=1 and cate_id=87 order by put_time desc,id desc limit $num,4";
   		$result = $this->db->getAll($sql);
   		return $result;     	
   }
}
?>
