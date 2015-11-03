<?php
/*-----------------------------------------------------+
 * 添加个人资料
 * 
 * @author maorenqi 
 +-----------------------------------------------------*/
class Profile extends Page{
    
    var $AuthLevel = ACT_OPEN;
    var $db;
	var $currentId;
    var $tab = 'member';
   // var $tab_group = 'sys_group';
    
   function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
    }

    
    function process(){
        //获取当前的操作id
        $this->currentId = $this->sess->get('memberid');
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
        Core::redirect(Core::getUrl('','','','',true));
    }

    
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
			'zjlx' => $this->lang->get('zjlx'),
			'jycd' => $this->lang->get('jycd'),
			'zy' => $this->lang->get('zy'),
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_article_editTitle'),
            'formAct' => Core::getUrl('','','','','true'),
        );
        $this->assign('v', stripQuotes($pvar));
        //$this->addTplFile('form');
        $this->display();
    }

    
    function getJsonData($item, $emsg=null){
        //如果只支持一种语言,则给lang加上默认值.
        if(count($this->conf->get('language_support')) < 2){
            $item['lang'] = strlen($item['lang']) ? $item['lang'] : $this->conf->get('language_default');
        }
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
           
            'goBackLink' =>array('url' => Core::getUrl('','','',true)),
        );
    }

    
    function getData(){
        $sql = "select * from {$this->tab} where id={$this->currentId}";
        $item = $this->db->GetRow($sql);
       
        return $item;
    }

    
    function updateDb($item){
        unset($item['id']);//防止ID号被修改
       //vardump($item);exit;
        $sql = "select * from {$this->tab} where id={$this->currentId}";
        $sql = $this->db->GetUpdateSQL($this->db->Execute($sql), $item);
        return $this->db->Execute($sql);
    }
    
    function validate($i){
        if(!$i['nickname']){
            PhpBox('昵称不能为空！！！!');
    		GotoPage(h);
    		exit;
        }
		if(!$i['name']){
            PhpBox('真实姓名不能为空！！！!');
    		GotoPage(h);
    		exit;
        }
		
    }
}
?>