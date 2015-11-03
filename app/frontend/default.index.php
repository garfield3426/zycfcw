<?php

class Index extends Page{
    var $AuthLevel = ACT_OPEN;
    var $db;
    var $tab="album";
    var $tab_photo="album_photo";
    var $tab_article="article";
    var $tab_doctor="doctor";
    
    
    function __construct(){
        parent::__construct();

        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_photo = $this->conf->get('table_prefix').$this->tab_photo;
        $this->tab_article = $this->conf->get('table_prefix').$this->tab_article;
        $this->tab_doctor = $this->conf->get('table_prefix').$this->tab_doctor;

        $this->db = getDb();
    }
    
    function process(){
    	$pvar = array(
    		'article_gushi' => $this->getArticle(),
    		'doctor' => $this->getDoctor(),
    		//search form Action
            'formAct' => Core::getUrl('showlist', 'article', array('page'=>0), true,true),  
            'album' => $this->getAlbum(),  
           
        );
       
        //设定模板使用变量
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }
    
    function getAlbum(){
    	$sql = "select id,title,img,put_time,cate_id from {$this->tab} where state=1 and cate_id in(90) order by is_order desc,id desc limit 0,1";
    	$result=$this->db->getAll($sql);
    
    	$list = array();
    	foreach($result as &$i){
            $i['info'] = utf_substr($this->getAlbumInfo($i['id']),66);
            $i['viewLink'] = Core::getUrl('view', 'album', array('cate_id'=>$i['cate_id'],'id'=>$i['id']),'',true);
           $list[]=$i;
        }
      
        return $list;
    }
    
    //获取第一张图简介
    function getAlbumInfo($album_id){
    	$sql = "SELECT info FROM {$this->tab_photo} WHERE album_id=$album_id ORDER BY is_order DESC,id DESC ";  	
    	return $this->db->GetOne($sql);
    }
    
    //获取患者故事信息
    function getArticle(){
    	$sql="SELECT id,title,img FROM {$this->tab_article} WHERE state=1 and img<>'' and cate_id=93 ORDER BY is_order DESC,id DESC LIMIT 0,15";
    	$result=$this->db->getAll($sql);
    	
    	return $result;
    	
    }
    function getDoctor(){
    	$sql="SELECT id,img,name,duty,rank,h_id,b_id FROM {$this->tab_doctor}  WHERE state = 1 ORDER BY is_order DESC,id DESC limit 0,10 ";
    	$result=$this->db->getAll($sql);
    	$list = array();
    	foreach($result as &$i){
    		$i['viewLink'] = "/index-doctor-view-id-{$i['id']}.html";
    		$i['hospitalName'] = getHospitalName($i['h_id']);
    		$i['branchName'] = getBranchName($i['b_id']);
    		$list[]=$i;
    	}
    	return $list;

    }
}
?>
