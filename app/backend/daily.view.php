<?php
/*-----------------------------------------------------+
 * 查看日志详细页
 *
 * @author maorenqi
 +-----------------------------------------------------*/
//引入类别处理的类

class View extends Page{
    var $AuthLevel = ACT_OPEN;
    var $db;
    
    var $currentId;
    var $tab = 'daily';
    var $tab_user = 'sys_user';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_user = $this->conf->get('table_prefix').$this->tab_user;
        $this->db = getDb();
        
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
        //页面输出的数据
        $_SESSION['query_data']['id'] = $this->currentId;
        $pvar = array(
            'item' => $item,
           	//form Action
            'formAct' => Core::getUrl('showlist', 'article', array('page'=>0), true,true),
			//手术类型
            'bespeak_type' => unserialize(CLIENT_TYPE),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }

    
    function getData(){
        $sql = "SELECT i.*, u.username as editor from {$this->tab} i left join {$this->tab_user} u on i.editor=u.id WHERE i.id={$this->currentId}";
        $item = $this->db->GetRow($sql);
        $item['put_time'] = date('Y-m-d',$item['put_time']);
        
        return $item;
    }
    
    
    
}
?>
