<?php
/*-----------------------------------------------------+
 * 发布相册
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
include_once(LIB_DIR.'/category.class.php');
class Create extends Page{
    var $db;
    var $tab = 'album';
   
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
    
        //检查是否提交数据
        if(!empty($this->input['submit'])){
            $this->submit();
            return;
        }
        //输出页面
        $this->export();
    }

    /**
     * 提交数据
     */
    function submit(){
    	
        //获取提交的数据
        $item = stripQuotes(trimArr($this->input['item']));
       
        //检查数据合法性
        $emsg = $this->validate($item);
        //如果数据不合法则输出
        if(count($emsg)){
            $this->export($item, $emsg);
            return;
        }

        //数据合法则写入数据
        if(!$this->insertDb($item,$counts)){
            //写入失败则输出信息
            Core::raiseMsg($this->lang->get('e_album_invalidationManipulation'));
        }
     
        Core::redirect(Core::getUrl('add','photo',array(album_id=>$this->db->insert_id())));
        
    }

    /**
     * 显示页面
     */
    function export($item=null, $emsg=null){
    
        $pvar = array(
            'item' => $item,
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_album_createTitle'),
            'formAct' => Core::getUrl(),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->addTplFile('form');
        $this->display();
    }

    /**
     * 获得需要JSON输出的数据
     * @param array $item
     * @return array
     */
    function getJsonData($item, $emsg=null){
        return array(
            'emsg' => $emsg,
            'item[state]' => array(
                'display' => true,
                'value' => 1,
                'list' => array(
                    '0' => $this->lang->get('global_stateDisabled'),
                    '1' => $this->lang->get('global_stateEnabled'),
                    //'2' => $this->lang->get('global_stateDelete'),
                ),
            ),
            
            //类别选择器
            'item[cate_id]' => Category::getSelList(
                $item['cate_id'],
                $this->conf->get('albumCateId'),
                false,
                '',
                false,
                $this->lang->get('j_global_cateSelDisableMsg')
            ),
            
            'goBackLink' =>array('url' => Core::getUrl('showlist','','',true)),
        );
    }

    /**
     * 写入数据
     */
    function insertDb($item,$counts){
        unset($item['id']);//防止ID号被修改
             
        $item['put_time'] = time();
        $item['open'] = 1;
      
        $sql = "select * from {$this->tab} where id=-1";
        $sql = $this->db->GetInsertSQL($this->db->Execute($sql), $item);
        
        return $this->db->Execute($sql);
         
    }
    
    function validate($i){
        $e = array();
        if(!is_numeric($i['cate_id'])){
            $e['cate_id'] = $this->lang->get('e_article_cateIsEmpty');
        }
        if(!$i['title']){
            $e['title'] = $this->lang->get('e_album_titleIsEmpty');
        }
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
