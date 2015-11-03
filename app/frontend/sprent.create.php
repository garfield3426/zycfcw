<?php
/*-----------------------------------------------------+
 * 发布二手房
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
include_once(LIB_DIR.'/category.class.php');
class Create extends Page{
    //var $AuthLevel = ACT_OPEN;
    var $AuthLevel = ACT_NEED_LOGIN;
    var $db;
    var $tab = 'esfang';

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
        Core::redirect(Core::getUrl('showlist','','',true));
    }

    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_article_createTitle'),
            'formAct' => Core::getUrl(),
        );
		//echo "<pre>";
		//print_r($pvar[ 'jsonData']['item[qy]']['list']); //显示区域信息
        $this->assign('v', stripQuotes($pvar));
        $this->addTplFile('form');
        $this->display();
    }

    function getJsonData($item, $emsg=null){
        return array(
            'emsg' => $emsg,
            
           /*  'item[state]' => array(
                'display' => true,
                'value' => 0,
                'list' => array(
                    '0' => $this->lang->get('global_stateDisabled'),
                    '1' => $this->lang->get('global_stateEnabled'),
                    //'2' => $this->lang->get('global_stateDelete'),
                ),
            ), */

            //类别选择器
			//区域
            'item[qy]' => Category::getSelList( $item['qy'], $this->conf->get('quyuCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[xx_xx]' => Category::getSelList( $item['xx_xx'], $this->conf->get('xiaoxueCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[xx_cz]' => Category::getSelList( $item['xx_cz'], $this->conf->get('cuzhongCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[dt]' => Category::getSelList( $item['dt'], $this->conf->get('ditieCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[cx]' => Category::getSelList( $item['cx'], $this->conf->get('chaoxiangCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[zxcd]' => Category::getSelList( $item['zxcd'], $this->conf->get('zxcdCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[ts]' => Category::getSelList( $item['ts'], $this->conf->get('teseCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[sq]' => Category::getSelList( $item['sq'], $this->conf->get('shangquanCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[fwpt]' => Category::getSelList( $item['fwpt'], $this->conf->get('fwptCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[dg]' => Category::getSelList( $item['dg'], $this->conf->get('dianguiCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),

            'goBackLink' =>array('url' => Core::getUrl('showlist','','',true)),
        );
    }

    /*
	*插入数据
	*/
    function insertDb($item){
        unset($item['id']);//防止ID号被修改
        //$item['content'] = absoluteToRelative($item['content']);
		if(is_array($item['fwpt'])){
			$item['fwpt'] = implode(',',$item['fwpt'] );
		}
		$item['state'] =1;
        if($item['is_order']){
        	$item['is_order']=$this->countOrder()+1;
        }
        $item['put_time'] = time();

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
        if(!$i['title']){
            PhpBox('标题不能为空！！！!');
    		GotoPage(h);
    		exit;
        }elseif($this->isExist($i['title'])){
            PhpBox('标题已经存在!');
    		GotoPage(h);
    		exit;
        }

        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
