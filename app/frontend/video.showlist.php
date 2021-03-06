<?php

//引入类别处理的类
include_once(LIB_DIR.'/category.class.php');
//引入分页处理的类
include_once(LIB_DIR.'/pager.class.php');

class ShowList extends Page{
    var $AuthLevel = ACT_OPEN;
    var $db;
    var $row = 9;
    var $currentPage = 0;
    var $filter;
    var $tab = 'video';
    var $tab_user = 'sys_user';
    var $tab_category = 'category';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_user = $this->conf->get('table_prefix').$this->tab_user;
        $this->tab_category = $this->conf->get('table_prefix').$this->tab_category;
        $this->db = getDb();
        $this->row = (5 <= $this->input['row'] && 100 >= $this->input['row']) ? (int)$this->input['row'] : $this->row;
        $this->currentPage = is_numeric($this->input['page']) ? (int)$this->input['page'] : $this->currentPage;
        $this->filter = $this->filter();
    }

    
    function process(){
    	
    	$_GET['cate']=$this->input['cate']?$this->input['cate']:'82';
        $pvar = array(
            //获得过滤器
            'kw' => $this->filter,
            //获得输出列表
            'list' => $this->getData(),
            //视频分类
            'cate' => Category::getListTop(82,'index-video-showlist'),
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
            "SELECT i.id as id, i.logo as logo, i.title as title, i.cate_id as cate_id, i.name as name, i.put_time as put_time, u.username as editor
            FROM {$this->tab} i LEFT JOIN {$this->tab_user} u ON i.editor=u.id";
        $sqlWhere = $this->getSqlWhere();
        $sqlOrder = " ORDER BY i.seq DESC,i.id DESC";
        //保存查询数据到$list
        $list = array();
        $rs = $this->db->SelectLimit($sqlQuery.$sqlWhere.$sqlOrder, $this->row, $this->row * $this->currentPage);
        if(!$rs->RecordCount() && $this->currentPage !=0){
            $this->currentPage = 0;
            $rs = $this->db->SelectLimit($sql,$this->row,$this->row * $this->currentPage);
        }
        while(!$rs->EOF){
            $rs->fields['viewLink'] = Core::getUrl('view', '', array('cate_id'=>$rs->fields['cate_id'],'id'=>$rs->fields['id']),'',true);
            $rs->fields['put_time'] = date($this->conf->get('dateFormat') ,$rs->fields['put_time']);
            $list[]=$rs->fields;
            $rs->MoveNext();
        }
      /*  echo "<pre>";
        print_r($list);*/
        return $list;
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
        $sqlWhere = " WHERE i.state = 1";
        $sqlWhere .= isset($this->filter['title'])  ? " AND i.title LIKE('%{$this->filter['title']}%')" : '';  
        $cateId = isset($this->filter['cate']) ? $this->filter['cate'] : $this->conf->get('articleCateId');
        $sqlWhere .= " AND i.cate_id IN (".implode(Category::getAllChild($cateId),',').")";
       // echo $sqlWhere;
        return $sqlWhere;
    }

    
    function getTotal($sqlWhere){
        return $this->db->GetOne("SELECT count(*) AS total FROM {$this->tab} i LEFT JOIN {$this->tab_user} u ON i.editor=u.id {$sqlWhere}");
    }
}
?>
