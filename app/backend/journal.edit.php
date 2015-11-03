<?php
/*-----------------------------------------------------+
 * 修改公文包
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
include_once(LIB_DIR.'/category.class.php');
class Edit extends Page{
    var $db;
    var $currentId;
    var $cate_id;
    var $tab = 'notice';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
    }

    
    function process(){
    	if(is_numeric($this->input['cate_id'])){
    		$this->cate_id = (int)$this->input['cate_id'];
    	}
        //获取当前的操作id
        $this->currentId = is_numeric($this->input['id'])
            ? $this->input['id']
            : $this->input['item']['id'];
        //没有则返回错误信息
        if(!is_numeric($this->currentId)){
            Core::raiseMsg($this->lang->get('e_article_idIsEmpty'));
        }
        //检查是否提交数据
        if(!empty($this->input['submit'])){
            $this->submit();
            return;
        }
        //输出页面
        $this->export(stripQuotes($this->getData()));
    }

    
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
        //数据合法则更新数据
        $this->updateDb($item);
        //返回列表页
        Core::redirect(Core::getUrl('showlist','notice','',true));
    }
    
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'disable' => 'disable',
            'url' => "/admin-notice-create-cate_id-{$this->cate_id}.html",
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_notice_editTitle'),
            'formAct' => Core::getUrl(),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->addTplFile('form');
        $this->display();
    }
    
    function getJsonData($item, $emsg=null){
        
        return array(
            'emsg' => $emsg,
            
            'item[state]' => array(
                'value' => $item['state'],
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
    
    function getData(){
        $sql = "select * from {$this->tab} where id={$this->currentId}";
        $item = $this->db->GetRow($sql);
        $item['put_time'] = date('Y-m-d H:i:s', $item['put_time']);
        $item['content'] = relativeToAbsolute($item['content']);
        return $item;
    }
    
    function updateDb($item){
        unset($item['id']);//防止ID号被修改
        $item['content'] = absoluteToRelative($item['content']);
        $item['put_time'] = strlen($item['put_time'])?strtotime($item['put_time']):time();
        $sql = "select * from {$this->tab} where id={$this->currentId}";
        $sql = $this->db->GetUpdateSQL($this->db->Execute($sql), $item);
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
