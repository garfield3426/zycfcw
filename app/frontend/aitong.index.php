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
		$h_id=$this->input['h_id'];
		$info=array('p0931'=>array('swt'=>'http://dlt.zoosnet.net/LR/Chatpre.aspx?id=DLT40739965'),
							'p028'=>array('swt'=>'http://plt.zoosnet.net/LR/Chatpre.aspx?id=PLT23179230'),
							'p0371'=>array('swt'=>'http://www.p0371.com/swt/zx.html'),
							'p0551'=>array('swt'=>'http://plt.zoosnet.net/LR/Chatpre.aspx?id=PLT68265179'),
							'p0871'=>array('swt'=>'http://service.p0871.com/LR/Chatpre.aspx?id=LUC42019326&lng=cn'),
							'p0991'=>array('swt'=>'http://www.p0991.com/zx.html'),
							'pr0791'=>array('swt'=>'http://lut.zoosnet.net/LR/Chatpre.aspx?id=LUT38351990'),
							'p023'=>array('swt'=>'http://plt.zoosnet.net/LR/Chatpre.aspx?id=PLT64903050'),
							'pr02'=>array('swt'=>'http://www.pr021.com/zx.html'),
							'p0451'=>array('swt'=>'http://pet.zoosnet.net/LR/Chatpre.aspx?id=PET75978813'),
							'p010'=>array('swt'=>'http://www.bjpryk.com/zx.html'),
							'pr027'=>array('swt'=>'http://lut.zoosnet.net/LR/Chatpre.aspx?id=LUT14851323'));
		if($h_id){
			$swt=$info[$h_id]['swt'];
		}else{
			$swt=$info['p010']['swt'];
		}

    	$pvar = array(
    		//'total' => $this->getBespeak(),
    		//search form Action
            'formAct' => Core::getUrl('showlist', 'article', array('page'=>0), true,true),  
			'swt' => $swt,
			'article_at1' => $this->getArticle(99,0,1),
			'article_at2' => $this->getArticle(99,1,1),
			'article_at3' => $this->getArticle(99,2,1),
			'article_zx1' => $this->getArticle(100,0,3),
			'article_zx2' => $this->getArticle(100,3,3),
        );
       
        //设定模板使用变量
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }
	
	 //获取患者故事信息
    function getArticle($cate_id,$start,$limit){
    	$sql="SELECT id,title,img,describes FROM {$this->tab_article} WHERE state=1 and img<>'' and cate_id=$cate_id ORDER BY is_order DESC,id DESC LIMIT $start,$limit";
    	$result=$this->db->getAll($sql);
    	return $result;
    	
    }
   
}
?>