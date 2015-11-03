<?php
/*-----------------------------------------------------+
 * 添加系统分类
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
class Create extends Page{
    var $AuthLevel = ACT_NEED_LOGIN;
    var $db;
    var $pid = 1;
    var $tab = 'category';

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
        if($this->input['pid']){
            $this->pid = $this->input['pid'];
        }elseif($this->input['item']['pid']){
            $this->pid = $this->input['item']['pid'];
        }elseif($this->input['id']){
            $this->pid = $this->input['id'];
        }
        //检查是否提交数据
        if(!empty($this->input['submit'])){
            $this->submit();
            return;
        }
        //输出页面
        $this->export(array('pid'=>$this->pid));
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
        $this->insertDb($item);
        Core::redirect(Core::getUrl('showlist','',array('kw_pid'=>$this->pid),true));
    }

    /**
     * 显示页面
     */
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_systemCategory_createTitle'),
            'formAct' => Core::getUrl(),
            'nodePath' => $this->getNodePath(),
        );
        $this->assign('v', stripQuotes($pvar));
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
            'goBackLink' =>array('url' => Core::getUrl('showlist','','',true)),
        );
    }

    /**
     * 获得当前分类路径
     * @return array
     */
    function getNodePath() {
        $path = array();
        $sql = "select c2.id as id, c2.title_zh as title_zh from {$this->tab} c1, {$this->tab} c2 where c1.lft between c2.lft and c2.rgt and c1.id={$this->pid} order by c2.lft asc";
        $rs = $this->db->Execute($sql);
        while ($rs && !$rs->EOF) {
            $enTitle = strlen($rs->fields['title_en']) ? "({$rs->fields['title_en']})" : '';
            $path[] =  array(
                'link' => Core::getUrl('','',array('kw_pid'=>$rs->fields['id']),true),
                'title' => $rs->fields['title_zh'].$enTitle
            );
            $rs->MoveNext();
        }
        return $path;
    }

     /**
     * 写入数据
	*新添加功能：批量添加，以','隔开的数据，如:类别1,类别2,类别3
     */
    function insertDb($item){
		$sql = "select max(id) from {$this->tab}";
		$item['id'] = $this->db->GetOne($sql) + 1;
		$item['pid'] = $this->pid;
		if(!class_exists('Treenode')) include_once(LIB_DIR.'/treenode.class.php');
		$node = new Treenode($this->tab, $this->db);
		$node->addChild($item);
		$node->buildTree(1,1);
		$node->updateLevel(1);
		$node->buildTree(1,1);
		IO::removeFile(VAR_DIR.'/category/');		//清除缓存
		
    }
     /**
     * 检查用户输入
     */
    function validate($i){
        $e = array();
        if(!$i['title_zh']){
            $e['title_zh'] = $this->lang->get('e_systemCategory_titleIsEmpty');
        }
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
