<?php
/*-----------------------------------------------------+
 * 显示视频详细页
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
    var $tab = 'video';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
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
            //分类标题
            'cate' => Category::getTitle($item['cate_id']),
            //视频分类
            'cate' => Category::getListTop(82,'index-video-showlist'),
            //推荐视频
            'comVideo' => $this->getComVideo($this->cate_id),
           	//search form Action
            'formAct' => Core::getUrl('showlist', 'article', array('page'=>0), true,true),
			//手术类型
            'bespeak_type' => unserialize(CLIENT_TYPE),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }

    //获取视频信息
    function getData(){
        $sql = "SELECT * FROM {$this->tab} WHERE id={$this->currentId}";
        $item = $this->db->GetRow($sql);
        if(!$item){
        	PhpBox("视频文件不存在!");
			GotoPage('/');
			exit;
        }
        
        return $item;
    } 
    
    function getComVideo($cateId){
    	$sql = "SELECT id,title,logo,cate_id FROM {$this->tab} 	WHERE cate_id={$cateId} order by seq desc,id desc limit 0,3";
    	$rs = $this->db->getAll($sql);
    	
    	return $rs;
    }
      
}
?>
