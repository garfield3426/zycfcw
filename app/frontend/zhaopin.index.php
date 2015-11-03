<?php
class Index extends Page{
    var $AuthLevel = ACT_OPEN;
    var $db;
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
            'formAct' => Core::getUrl('showlist', 'article', array('page'=>0), true,true),  
			'article_zx' => $this->getArticle(102,0,6),
			'article_gs' => $this->getArticle(103,0,6),
        );
        //设定模板使用变量
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }
	
	 //获取患者故事信息
    function getArticle($cate_id,$start,$limit){
    	$sql="SELECT id,title,img,content,describes FROM {$this->tab_article} WHERE state=1 and img<>'' and cate_id=$cate_id ORDER BY is_order DESC,id DESC LIMIT $start,$limit";
    	$result=$this->db->getAll($sql);
    	return $result;
    }
   
}
?>