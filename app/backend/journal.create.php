<?php
/*-----------------------------------------------------+
 * 发布公文包
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
include_once(LIB_DIR.'/category.class.php');
class Create extends Page{
    var $db;
    var $cate_id;
    var $tab = 'notice';
    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
    }
    
    function process(){
    	//print_r($_SERVER[SCRIPT_URL] );
    	if(is_numeric($this->input['cate_id'])){
    		$this->cate_id = (int)$this->input['cate_id'];
    	}
    	//echo $this->cate_id ;
        //检查是否提交数据
        if(!empty($this->input['submit'])){
            $this->submit();
            return;
        }
        //输出页面
        $this->export();
    }
    
    function submit(){
        //获取提交的数据
        $item = stripQuotes(trimArr($this->input['item']));
        $item['cate_id'] = $this->cate_id;
        print_r($item);
        //检查数据合法性
        $emsg = $this->validate($item);
        //如果数据不合法则输出
        if(count($emsg)){
            $this->export($item, $emsg);
            return;
        }
        //数据合法则写入数据
        if(!$this->insertDb($item)){
            //写入失败则输出信息
            Core::raiseMsg($this->lang->get('e_notice_invalidationManipulation'));
        }
        Core::redirect(Core::getUrl('showlist','notice'));
    }
    
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'jsonData' => $this->getJsonData($item, $emsg),
            'url' => $_SERVER[SCRIPT_URL],
            'title' => $this->lang->get('p_notice_createTitle'),
            'formAct' => Core::getUrl('','',array('cate_id'=>$this->cate_id)),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->addTplFile('form');
        $this->display();
    }
    
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
                $this->conf->get('noticeCateId'),
                false,
                '',
                false,
                $this->lang->get('j_global_cateSelDisableMsg')
            ),
          
            'goBackLink' =>array('url' => Core::getUrl('showlist','','',true)),
        );
    }

    function insertDb($item){
        unset($item['id']);//防止ID号被修改
        
        $item['content'] = absoluteToRelative($item['content']);
        $item['editor'] = $this->sess->getUserId();
        $item['put_time'] = strlen($this->input['put_time'])?ext('unixtime',$this->input['put_time']):time();
        $sql = "select * from {$this->tab} where id=-1";
        $sql = $this->db->GetInsertSQL($this->db->Execute($sql), $item);
        return $this->db->Execute($sql);
    }
   
    function validate($i){
        $e = array();
        if(!is_numeric($i['state'])){
            $e['state'] = $this->lang->get('e_notice_stateIsEmpty');
        }
        if(!is_numeric($i['cate_id'])){
            $e['cate_id'] = $this->lang->get('e_notice_cateIsEmpty');
        }
        if(!$i['title']){
            $e['title'] = $this->lang->get('e_notice_titleIsEmpty');
        }
        if(!strlen($i['content'])){
            $e['content'] = $this->lang->get('e_notice_contentIsEmpty');
        }
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
