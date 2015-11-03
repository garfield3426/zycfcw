<?php
/*-----------------------------------------------------+
 * 发布新资讯
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
include_once(LIB_DIR.'/category.class.php');
include_once(LIB_DIR.'/formelem.php');
class Create extends Page{
    var $db;
    var $tab = 'article';

    
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
            'xx' => $xuexiao,
            'title' => $this->lang->get('p_article_createTitle'),
            'formAct' => Core::getUrl(),
        );
		/* $xuexiao = Form::checkbox('item[xx]',$pvar[jsonData]['item[dt]']['list']);
		echo '<pre>';
		print_r($xuexiao); */
		//print_r($pvar[jsonData]['item[dt]']['list']);
        $this->assign('v', stripQuotes($pvar));
        $this->addTplFile('form');
        $this->display();
    }

    
    function getJsonData($item, $emsg=null){
       
        return array(
            'emsg' => $emsg,
            
            'item[state]' => array(
                'display' => true,
                'value' => 0,
                'list' => array(
                    '0' => $this->lang->get('global_stateDisabled'),
                    '1' => $this->lang->get('global_stateEnabled'),
                    //'2' => $this->lang->get('global_stateDelete'),
                ),
            ),

            //类别选择器
            'item[cate_id]' => Category::getSelList( $item['cate_id'],$this->conf->get('articleCateId'),false,'', false, $this->lang->get('j_global_cateSelDisableMsg')),
            'item[cx]' => Category::getSelList( $item['cx'],$this->conf->get('chaoxiangCateId'),false,'', false, $this->lang->get('j_global_cateSelDisableMsg')),
            'item[dt]' => Category::getSelList( $item['dt'],$this->conf->get('ditieCateId'),true, '', false, $this->lang->get('j_global_cateSelDisableMsg')),
            'item[xx]' => Category::getSelList( $item['xx'],$this->conf->get('xuexiaoCateId'),false,'', false, $this->lang->get('j_global_cateSelDisableMsg')),
            'item[zx]' => Category::getSelList( $item['zx'],$this->conf->get('zxcdCateId'),false,'', false, $this->lang->get('j_global_cateSelDisableMsg')),
            'item[qy]' => Category::getSelList( $item['qy'],$this->conf->get('quyuCateId'),false,'', false, $this->lang->get('j_global_cateSelDisableMsg')),
            'item[ts]' => Category::getSelList( $item['ts'],$this->conf->get('teseCateId'),false,'', false, $this->lang->get('j_global_cateSelDisableMsg')),
            'goBackLink' =>array('url' => Core::getUrl('showlist','','',true)),
        );
    }

    
    function insertDb($item){
        unset($item['id']);//防止ID号被修改
        $item['content'] = absoluteToRelative($item['content']);
        $item['editor'] = $this->sess->getUserId();
        $item['h_id'] = CONTENT_SKY;
        if($item['is_order']){
        	$item['is_order']=$this->countOrder()+1;
        }
        if(!$item['editor1']){
        	$item['editor1']='忠业诚';
        }
        $item['put_time'] = strlen($this->input['put_time'])?ext('unixtime',$this->input['put_time']):time();
        $sql = "select * from {$this->tab} where id=-1";
        $sql = $this->db->GetInsertSQL($this->db->Execute($sql), $item);
        return $this->db->Execute($sql);
    }

    
    function isExist($title){
        $sql = "select count(*) from {$this->tab} where title='{$title}'";
        return $this->db->GetOne($sql) ? true : false;
    }

    
    function countOrder(){
        $sql = "select is_order from {$this->tab} where is_order<>0 order by  is_order desc";
        return $this->db->GetOne($sql);
    }
    
    
    function validate($i){
        $e = array();
        if(!is_numeric($i['state'])){
            $e['state'] = $this->lang->get('e_article_stateIsEmpty');
        }
        if(!is_numeric($i['cate_id'])){
            $e['cate_id'] = $this->lang->get('e_article_cateIsEmpty');
        }
        if(!$i['title']){
            $e['title'] = $this->lang->get('e_article_titleIsEmpty');
        }elseif($this->isExist($i['title'])){
            $e['title'] = $this->lang->get('e_article_titleIsExist');
        }
        if(!strlen($i['author'])){
            $e['author'] = $this->lang->get('e_article_authorIsEmpty');
        }
        if(!$i['keyword']){
            $e['keyword'] = $this->lang->get('e_article_keywordIsEmpty');
        }
        if(!$i['describes']){
            $e['describes'] = $this->lang->get('e_article_describesIsEmpty');
        }
        if(!strlen($i['content'])){
            $e['content'] = $this->lang->get('e_article_contentIsEmpty');
        }
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
