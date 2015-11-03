<?php
/*-----------------------------------------------------+
 * 发布友情链接
 *
 * @author maorenqi 
 +-----------------------------------------------------*/

class Create extends Page{
    var $db;
    var $tab = 'link';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
    }

    
    function process(){
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
            Core::raiseMsg($this->lang->get('e_article_invalidationManipulation'));
        }
        Core::redirect(Core::getUrl('showlist'));
    }

    
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_link_createTitle'),
            'formAct' => Core::getUrl(),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->addTplFile('form');
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
                'display' => true,
                'value' => $item['state'],
                'list' => array(
                    '0' => $this->lang->get('global_stateDisabled'),
                    '1' => $this->lang->get('global_stateEnabled'),
                    //'2' => $this->lang->get('global_stateDelete'),
                ),
            ),
            'item[lang]' => array(
                'value' => $item['lang'],
                'list' => $this->lang->getSelectList(),
            ),
            'goBackLink' =>array('url' => Core::getUrl('showlist','','',true)),
        );
    }

    
    function insertDb($item){
        unset($item['id']);//防止ID号被修改
        $item['intro'] = absoluteToRelative($item['intro']);
        $item['editor'] = $this->sess->getUserId();
        $item['put_time'] = time();
        $sql = "select * from {$this->tab} where id=-1";
        $sql = $this->db->GetInsertSQL($this->db->Execute($sql), $item);
        return $this->db->Execute($sql);
    }

    
    function isExist($title){
        $sql = "select count(*) from {$this->tab} where title='{$title}'";
        return $this->db->GetOne($sql) ? true : false;
    }

    
    function validate($i){
        $e = array();
        if(!$i['title']){
            $e['title'] = $this->lang->get('e_link_titleIsEmpty');
        }elseif($this->isExist($i['title'])){
            $e['title'] = $this->lang->get('e_link_titleIsExist');
        }
        
        if(!$i['url']){
            $e['url'] = $this->lang->get('e_link_urlIsEmpty');
        }
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
