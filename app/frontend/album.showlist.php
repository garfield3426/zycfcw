<?php

//引入类别处理的类
include_once(LIB_DIR.'/category.class.php');
//引入分页处理的类
include_once(LIB_DIR.'/pager.class.php');

class ShowList extends Page{
    var $AuthLevel = ACT_OPEN;
    var $db;
    var $row = 5;
    var $currentPage = 0;
    var $filter;
    var $tab = 'album';
    var $tab_photo = 'album_photo';
    var $tab_category = 'category';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_photo = $this->conf->get('table_prefix').$this->tab_photo;
        $this->tab_category = $this->conf->get('table_prefix').$this->tab_category;
        $this->db = getDb();
        //$this->db->debug = true;
        $this->row = (5 <= $this->input['row'] && 100 >= $this->input['row']) ? (int)$this->input['row'] : $this->row;
        $this->currentPage = is_numeric($this->input['page']) ? (int)$this->input['page'] : $this->currentPage;
        $this->filter = $this->filter();
    }

    
    function process(){
    	
    	$_GET['cate']=$this->input['cate']?$this->input['cate']:'88';
        $pvar = array(
            //获得过滤器
            'kw' => $this->filter,
            //获得输出列表
            'list' => $this->getData(),
            //视频分类
            'cate' => Category::getListTop(88,'index-album-showlist'),
            //search form Action
            'formAct' => Core::getUrl('showlist', 'article', array('page'=>0), true,true),
            //手术类型
            'bespeak_type' => unserialize(CLIENT_TYPE),
            //Pager
            'pager' => Pager::index($this->getTotal($this->getSqlWhere()), $this->currentPage, $this->row),
        );
        //设定模板使用变量
        $this->assign('v', stripQuotes($pvar));
        $this->assign('cate', $this->getCate($_GET['cate']));
        //显示页面
        $this->display();
    }
    
	
    function getCate($cate_id){
   		$sql="SELECT title,title_zh,title_en,description FROM {$this->tab_category} WHERE id=$cate_id";
   		return $this->db->getRow($sql);
   	}
   	
    
    function getData(){
        //sql查询语句
        $sqlQuery =
            "SELECT id,title,cate_id,img,put_time FROM {$this->tab} ";
        $sqlWhere = $this->getSqlWhere();
        $sqlOrder = " ORDER BY is_order DESC,id DESC";
        //保存查询数据到$list
        $list = array();
        $rs = $this->db->SelectLimit($sqlQuery.$sqlWhere.$sqlOrder, $this->row, $this->row * $this->currentPage);
        if(!$rs->RecordCount() && $this->currentPage !=0){
            $this->currentPage = 0;
            $rs = $this->db->SelectLimit($sql,$this->row,$this->row * $this->currentPage);
        }
        
        //$albumId = array();
        while(!$rs->EOF){
            $rs->fields['viewLink'] = Core::getUrl('view', '', array('cate_id'=>$rs->fields['cate_id'],'id'=>$rs->fields['id']),'',true);
            $rs->fields['put_time'] = date('Y.m.d' ,$rs->fields['put_time']);
            $list[]=$rs->fields;
            $albumId[]=$rs->fields['id'];
            $rs->MoveNext();
        }
       
        //$count = $this->getAlbumInfo($albumId);
        foreach($list as &$i){
            $i['info'] = $this->getAlbumInfo($i['id']);
        }
      /*  echo "<pre>";
        print_r($list);*/
        return $list;
    }

    //获取第一张图简介
    function getAlbumInfo($album_id){
    	$sql = "SELECT info FROM {$this->tab_photo} WHERE state=1 and album_id=$album_id ORDER BY is_order DESC,id DESC ";
    	return $this->db->GetOne($sql);
    }
    
    function filter(){
    	
        $filter = array();
        if(is_numeric($this->input['row']))      $filter['row'] =  $this->input['row'];
        if(is_numeric($this->input['page']))     $filter['page'] = $this->input['page'];
        if(strlen($this->input['title']))        $filter['title'] = $this->input['title'];
        if(is_numeric($this->input['cate']))     $filter['cate'] = $this->input['cate'];
        
        //保存过滤器到Session
        $this->sess->setQueryData($filter);
        return $filter;
    }

    
    function getSqlWhere(){
        $sqlWhere = " WHERE state=1 ";
        $sqlWhere .= isset($this->filter['title'])  ? " AND title LIKE('%{$this->filter['title']}%')" : '';  
        $cateId = isset($this->filter['cate']) ? $this->filter['cate'] :'88';
        $sqlWhere .= " AND cate_id IN (".implode(Category::getAllChild($cateId),',').")";
       // echo $sqlWhere;
        return $sqlWhere;
    }

    
    function getTotal($sqlWhere){
        return $this->db->GetOne("SELECT count(*) AS total FROM {$this->tab} {$sqlWhere}");
    }
}
?>
