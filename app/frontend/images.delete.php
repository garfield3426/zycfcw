<?php
/***************************************************************
 * 保存网上求医
 * 
 * @author maorenqi
 ***************************************************************/
//require_once LIB_DIR.'/category.class.php';
class Delete extends Page{
    var $AuthLevel = ACT_OPEN;
    var $db;
    var $tab = 'esfang';

    /**
     * 构造方法
     */
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
    }

    /**
     * 程序入口
     */
    function process(){
        $path = trimArr($this->input['path']);
		$item = $_SERVER[DOCUMENT_ROOT].'/zyc/uploads/'.$path;
echo $item ;
        if(!$this->delete($item)){
            PhpBox('删除失败！');
            //gotoPage(h);
            exit;
        }else{
            PhpBox("删除成功！");
            //gotoPageNull(h);
            exit;
        } 
		
    }

    /**
     * 写入数据
     */
    function delete($i){ 	
		if(!is_file($i)){
			PhpBox('没有该文件，终止操作！');
            //gotoPage(h);
            exit;
		}
		//echo $i;exit;
		return $result = @unlink ($i); 
    }

   
    
}
?>
