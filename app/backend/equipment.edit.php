<?php
/*-----------------------------------------------------+
 * 修改设备信息
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
//include_once(LIB_DIR.'/category.class.php');
class Edit extends Page{
    var $db;
    var $currentId;
    var $tab = 'equipment';
    
    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
    }

    
    function process(){
        //获取当前的操作id
        $this->currentId = is_numeric($this->input['id'])
            ? $this->input['id']
            : $this->input['item']['id'];
        //没有则返回错误信息
        if(!is_numeric($this->currentId)){
            Core::raiseMsg($this->lang->get('e_doctor_idIsEmpty'));
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
        Core::redirect(Core::getUrl('showlist','','',true));
    }

    
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_doctor_editTitle'),
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
                ),
            ),
           
            'goBackLink' =>array('url' => Core::getUrl('showlist','','',true)),
        );
    }

    
    function getData(){
        $sql = "select * from {$this->tab} where id={$this->currentId}";
        $item = $this->db->GetRow($sql);
        $item['content'] = relativeToAbsolute($item['content']);
        return $item;
    }

    
    function updateDb($item){
        unset($item['id']);//防止ID号被修改
        $item['content'] = absoluteToRelative($item['content']);
        $sql = "select * from {$this->tab} where id={$this->currentId}";
        $sql = $this->db->GetUpdateSQL($this->db->Execute($sql), $item);
        return $this->db->Execute($sql);
    }

    
    function isExist($title){
        $sql = "select count(*) from {$this->tab} where name='{name}' and id<>{$this->currentId}";
        return $this->db->GetOne($sql) ? true : false;
    }

    
    function validate($i){
        $e = array();
        if(!$i['name']){
            $e['name'] = $this->lang->get('e_doctor_nameIsEmpty');
        }    
        if(!is_numeric($i['state'])){
            $e['state'] = $this->lang->get('e_doctor_stateIsEmpty');
        }
        if(!strlen($i['keyword'])){
            $e['keyword'] = $this->lang->get('e_doctor_keywordIsEmpty');
        }
        if(!strlen($i['info'])){
            $e['info'] = $this->lang->get('e_doctor_infoIsEmpty');
        }
        if(!strlen($i['intro'])){
            $e['intro'] = $this->lang->get('e_doctor_introIsEmpty');
        }
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
