<?php
/*-----------------------------------------------------+
 * 查看资讯信息
 *
 * @author maorenqi
 +-----------------------------------------------------*/
//引入类别处理的类
include_once(LIB_DIR.'/category.class.php');
//引入分页处理的类
include_once(LIB_DIR.'/pager.class.php');
class View extends Page{
    var $AuthLevel = ACT_OPEN;
    var $db;
    
    var $currentId;
    var $tab = 'article';
    //var $tab_comment = 'comment';
    var $tab_user = 'sys_user';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        //$this->tab_comment = $this->conf->get( 'table_prefix').$this->tab_comment;
        $this->tab_user = $this->conf->get('table_prefix').$this->tab_user;
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
        //没有则返回错误信息
        if(!is_numeric($this->currentId)){
            //提示页面不存在
            Core::raiseMsg($this->lang->get('pegeIsInexistent'));
        }
        //输出页面
        $this->export(stripQuotes($this->getData()));
    }

    
    function export($item=null, $emsg=null){
    	//统计点击率
		$newsCookie = "news".$this->currentId;
		if(!isset($_COOKIE["$newsCookie"]))
		{
			setcookie ("$newsCookie", $newsCookie, time() + 86400);
			$sql="UPDATE {$this->tab} SET browse=browse+1 WHERE id=$this->currentId";
			$this->db->Execute($sql);
		}
    	
        //页面输出的数据
        $_SESSION['query_data']['id'] = $this->currentId;
        $pvar = array(
            'item' => $item,
            //'count' => $this->getCount(),
            //'comment' => $this->getComment(),
            //文章分类
            'cate' => Category::getListTop(2,'index-article-showlist'),
            //页面标题
            'title' => Category::getTitle($item['cate_id']),
            'sameArticle' => $this->getSameArticle($item['cate_id']),
            'preLink' => $this->getNextRow($item['id']),
            'nextLink' => $this->getPreRow($item['id']),
            //手术类型
            'bespeak_type' => unserialize(CLIENT_TYPE),
            //search form Action
            'formAct' => Core::getUrl('showlist', 'article', array('page'=>0), true,true),

        );
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }
	
    //获取评论总数
    function getCount(){
    	return $this->db->GetOne("SELECT count(*) as total FROM {$this->tab_comment} WHERE article_id=$this->currentId");
    }
    
    //获取评论内容
    function getComment(){
    	$sql = "SELECT * FROM {$this->tab_comment} WHERE state=1 and article_id=$this->currentId ORDER BY id DESC limit 5";   
    	//print_r($this->db->GetAll($sql));	
    	return $this->db->GetAll($sql);
    	
    }
    
    //获取相关文章数
    function getSameArticle($cate){
    	$sql = "SELECT id,title FROM {$this->tab} WHERE state=1 and cate_id in($cate) ORDER BY put_time DESC limit 10";  
    	$return = $this->db->GetAll($sql);
    	$list = array();
    	foreach($return as &$i){
    		$i['title_sub'] = utf_substr($i['title'],40);
    		$list[] = $i;
    	}	
    	
    	return $list;
    }
    
    //获取文章详细参数
    function getData(){
        $sql = "SELECT i.*, u.username as editor FROM {$this->tab} i left join {$this->tab_user} u on i.editor=u.id WHERE i.id={$this->currentId} AND i.state=1";
        $item = $this->db->GetRow($sql);
        if(!$item){
        	//提示页面不存在
            Core::raiseMsg($this->lang->get('pegeIsInexistent'));
        }
        $item['put_time'] = date($this->conf->get('dateFormat'), $item['put_time']);
        $item['content'] = relativeToAbsolute($item['content']);
        $item['viewLink'] = Core::getUrl('showlist', 'article', array('cate'=>$item['cate_id']),'',true);
        return $item;
    }
    
    //获取下一条记录
    function getNextRow($id){
        $sql = "SELECT id,title,cate_id FROM {$this->tab} WHERE id>{$id} and state=1 ORDER BY id ASC LIMIT 1";
        $item = $this->db->GetRow($sql);
        if($item){
            $url = Core::getUrl('view','',array('id'=>$item['id']),'',true);
            $str = "<a href=\"$url\" class=\"more_title\">{$item['title']}</a>";
        }else{
            $str = '无';
        }
        return $str;
    }
    
    //获取上一条记录
    function getPreRow($id){
        $sql = "SELECT id,title,cate_id FROM {$this->tab} WHERE id<{$id} and state=1 ORDER BY id DESC LIMIT 1";
        $item = $this->db->GetRow($sql);
        if($item){
            $url = Core::getUrl('view','',array('id'=>$item['id']),'',true);
            $str = "<a href=\"$url\" class=\"more_title\">{$item['title']}</a>";
        }else{
            $str = '无';
        }
        return $str;
    }
}
?>
