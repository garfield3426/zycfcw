<?php
/*-----------------------------------------------------+
 * 显示图册细页
 *
 * @author maorenqi
 +-----------------------------------------------------*/
//引入类别处理的类
//include_once(LIB_DIR.'/category.class.php');
//引入分页处理的类
include_once(LIB_DIR.'/category.class.php');

class View extends Page{
    var $AuthLevel = ACT_OPEN;
    var $db;
    
    var $currentId;
    var $cate_id;
    var $tab = 'album';
    var $tab_photo = 'album_photo';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_photo = $this->conf->get('table_prefix').$this->tab_photo;
        $this->db = getDb();
        $this->row = (5 <= $this->input['row'] && 100 >= $this->input['row']) ? (int)$this->input['row'] : $this->row;
        $this->currentPage = is_numeric($this->input['page']) ? (int)$this->input['page'] : $this->currentPage;
        //$this->db->debug = true;
    }

    
    function process(){
        //获取当前的操作id
        if(is_numeric($this->input['id'])){
            $this->currentId = (int)$this->input['id'];
        }
        if(is_numeric($this->input['cate_id'])){
        	$this->cate_id = (int)$this->input['cate_id'];
        }
        //没有则返回错误信息
        if(!is_numeric($this->currentId)){
            //提示页面不存在
            Core::raiseMsg($this->lang->get('pegeIsInexistent'));
        }
        //输出页面
        $this->export(stripQuotes($this->getData()));
    }

    
    function export($item=null, $emsg=null){
        //页面输出的数据
        $_SESSION['query_data']['id'] = $this->currentId;
        $pvar = array(
            'item' => $item,
            'title' => $this->getTitle(),
            //图册分类
            'cate' => Category::getListTop(88,'index-album-showlist'),
            //推荐图册
            'comAlbum' => $this->getComAlbum($this->cate_id,$this->currentId),
           	//search form Action
            'formAct' => Core::getUrl('showlist', 'article', array('page'=>0), true,true),
			//手术类型
            'bespeak_type' => unserialize(CLIENT_TYPE),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }
    
    function getTitle(){
    	$sql = "SELECT title FROM {$this->tab} WHERE id={$this->currentId}";
    	return $this->db->getOne($sql);
    }

    //获取图册信息
    function getData(){
        $sql = "SELECT title,id,info FROM {$this->tab_photo}  WHERE album_id={$this->currentId} AND state<>2  ORDER BY is_order DESC,id DESC";
        $item = $this->db->GetAll($sql);
        //print_r($item);
        if(!is_array($item)){
        	PhpBox("图册不存在!");
			GotoPage('/');
			exit;
        }
        /*echo "<pre>";
    	print_r($item);*/
        $list = array();
        foreach($item as & $i){
        	$i['info'] = utf_substr($i['info'],180);
        	$list[] = $i;
        	
        }
        return $list;
    } 
    
    function getComAlbum($cateId){
    	$sql = "SELECT id,title,img,cate_id FROM {$this->tab} 	WHERE cate_id={$cateId} and id<>{$this->currentId} ORDER BY is_order DESC,id DESC limit 0,3";
    	$rs = $this->db->getAll($sql);
    	/*echo "<pre>";
    	print_r($rs);*/
    	return $rs;
    }
      
}
?>
