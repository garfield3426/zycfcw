<?php
/*-----------------------------------------------------+
 * 修改资讯
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
include_once(LIB_DIR.'/category.class.php');
class Edit extends Page{
	var $AuthLevel = ACT_NEED_LOGIN;
    var $db;
    var $currentId;
    var $tab = 'esfang';

    
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
        Core::redirect(Core::getUrl('showlist','','','',true));
    }

    
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_article_editTitle'),
            'formAct' => Core::getUrl('','','','','true'),
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
                'value' => $item['state'],
                'list' => array(
                    '0' => $this->lang->get('global_stateDisabled'),
                    '1' => $this->lang->get('global_stateEnabled'),
                    //'2' => $this->lang->get('global_stateDelete'),
                ),
            ),
           //类别选择器
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

    
    function getData(){
        $sql = "select * from {$this->tab} where id={$this->currentId}";
        $item = $this->db->GetRow($sql);
       // $item['put_time'] = date('Y-m-d H:i:s', $item['put_time']);
        $item['content'] = relativeToAbsolute($item['content']);
        $item['fwpt']= explode(',',$item['fwpt'] );
        return $item;
    }

    
    function updateDb($item){
    	
		/* $items[editor1]='普瑞眼科编辑部';
    	$sql1 = "select * from {$this->tab} where state=1";
        $sql1 = $this->db->GetUpdateSQL($this->db->Execute($sql1), $items);
        $this->db->Execute($sql1); */
        unset($item['id']);//防止ID号被修改
        $item['content'] = absoluteToRelative($item['content']);
        $item['edit_time'] = time();
        //$item['put_time'] = strlen($item['put_time'])?strtotime($item['put_time']):time();
        $item['is_order'] = $item['is_order']?$this->countOrder()+1:0;
		if(is_array($item['fwpt'])){
			$item['fwpt'] = implode(',',$item['fwpt'] );
		}
        $sql = "select * from {$this->tab} where id={$this->currentId}";
        $sql = $this->db->GetUpdateSQL($this->db->Execute($sql), $item);
        return $this->db->Execute($sql);
    }

    
    function isExist($title){
        $sql = "select count(*) from {$this->tab} where title='{$title}' and id<>{$this->currentId}";
        return $this->db->GetOne($sql) ? true : false;
    }
    
    
    function countOrder(){
        $sql = "select is_order from {$this->tab} where is_order<>0 order by is_order desc";
        return $this->db->GetOne($sql)?$this->db->GetOne($sql):0;
    }

    
    function validate($i){
        if(!$i['title']){
            PhpBox('标题不能为空！！！!');
    		GotoPage(h);
    		exit;
        }
    }
}
?>
