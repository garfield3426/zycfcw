<?php
/*-----------------------------------------------------+
 * 修改相册
 *
 * @author maorenqi
 +-----------------------------------------------------*/

include_once(LIB_DIR.'/category.class.php');
class Edit extends Page{
    var $db;
    var $currentId;
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
        //获取当前的操作id
        $this->currentId = is_numeric($this->input['id'])
            ? $this->input['id']
            : $this->input['item']['id'];
        //没有则返回错误信息
        if(!is_numeric($this->currentId)){
            Core::raiseMsg($this->lang->get('e_album_idIsEmpty'));
        }
        //检查是否提交数据
        if(!empty($this->input['submit'])){
            $this->submit();
            return;
        }
        //输出页面
        $this->export(stripQuotes($this->getData()));
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
        $counts = count($this->input['imageTitle']);
        //数据合法则更新数据
        $this->updateDb($item,$counts,$this->input['imageTitle']);
        //返回列表页
        Core::redirect(Core::getUrl('showlist','','',true));
    }

    /**
     * 显示页面
     */
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_album_editTitle'),
            'formAct' => Core::getUrl(),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->addTplFile('form');
        $this->display();
    }

    /**
     * 获得需要JSON输出的数据
     * @param array $item
     * @param array $emsg
     * @return array
     */
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
     * 获取数据
     */
    function getData(){
        $sql = "select * from {$this->tab} where id={$this->currentId}";

        $item = $this->db->GetRow($sql);
        //print_r($item);
        $item['put_time'] = date('Y-m-d', $item['put_time']);
        return $item;
    }

    /**
     * 写入数据
     */
    function updateDb($item,$counts,$image){
        unset($item['id']);//防止ID号被修改
        $item['counts']=$item['counts']+$counts;
        $sql = "select * from {$this->tab} where id={$this->currentId}";
        $sql = $this->db->GetUpdateSQL($this->db->Execute($sql), $item);
        $result = $this->db->Execute($sql);
        
        
        return $result;
    }
    
    /**
     * 检查用户输入
     */
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
