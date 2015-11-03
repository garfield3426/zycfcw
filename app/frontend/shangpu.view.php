<?php
/*-----------------------------------------------------+
 * 查看二手房信息
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
    var $row = 5;
    var $currentPage = 0;
    var $currentId;
    var $tab = 'fang';

    /**
     * 构造方法
     */
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        $this->row = (5 <= $this->input['row'] && 100 >= $this->input['row']) ? (int)$this->input['row'] : $this->row;
        $this->currentPage = is_numeric($this->input['page']) ? (int)$this->input['page'] : $this->currentPage;
        //$this->db->debug = true;
    }

    /**
     * 程序入口
     */
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

    /**
     * 显示页面
     */
    function export($item=null, $emsg=null){
        //页面输出的数据
        $_SESSION['query_data']['id'] = $this->currentId;
        $pvar = array(
            'item' => $item,
            //根据分类显示列表页
            //'type_client' => $this->getDataList(),
           	//search form Action
            'formAct' => Core::getUrl('showlist', 'esfang', array('page'=>0), true,true),  
           // 'pager' => Pager::index($this->getTotal($this->getSqlWhere()), $this->currentPage, $this->row),
		
        );
        //vardump($pvar);	
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }

    /**
     * 获取数据
     */
    function getData(){
        $sql = "SELECT * FROM {$this->tab} WHERE id={$this->currentId}";
        $item = $this->db->GetRow($sql);
        $item['content'] = relativeToAbsolute($item['content']);
		if($item['cx']){
			$item['cx'] = Category::getTitle($item['cx']);
		}
		if($item['sn_images']){
			$item['sn_images']= explode(',',$item['sn_images'] );
		}
		if($item['xq_images']){
			$item['xq_images']= explode(',',$item['xq_images'] );
		}
		if($item['zxcd']){
			$item['zxcd'] = Category::getTitle($item['zxcd']);
		}
		if($item['jg']){
			$item['jg'] = Category::getTitle($item['jg']);
		}
		if($item['zzlb']){
			$item['zzlb'] = Category::getTitle($item['zzlb']);
		}
		if($item['jzlx']){
			$item['jzlx'] = Category::getTitle($item['jzlx']);
		}
		if($item['cqxz']){
			$item['cqxz'] = Category::getTitle($item['cqxz']);
		}
		if($item['kfsj']){
			$item['kfsj'] = Category::getTitle($item['kfsj']);
		}
		if($item['kjylb_sp']){
			$item['kjylb_sp']= explode(',',$item['kjylb_sp'] );
		}
		if($item['fwpt_sp']){
			$item['fwpt_sp']= explode(',',$item['fwpt_sp'] );
		}
       
        return $item;
    }
    
}
?>
