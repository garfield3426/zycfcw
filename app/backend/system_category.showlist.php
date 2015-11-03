<?php

class ShowList extends Page{
    var $db;
    var $filter;
    var $tab = 'category';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
        $this->filter = $this->filter();
    }

    
    function process(){
        if(!empty($this->input['submit'])){
            $this->sequence($this->input['sequence']);
            Core::redirect(Core::getUrl('','',array('kw_pid'=>$pid),true));
            return;
        }
        $pvar = array(
            'kw' => $this->filter,
            'list' => $this->getData(),
            'jsonData' => $this->getJsonData(),
            'title' => $this->lang->get('p_systemCategory_showlistTitle'),
            'formAct' => Core::getUrl('', '', array('page'=>0), true),
            'nodePath' => $this->getNodePath(),
        );
        //趨ģʹñ
        $this->assign('v', stripQuotes($pvar));
        //ʾҳ
        $this->display();
    }

    
    function getData(){
        //ѯݵ$list
        $list = array();
        $rs = $this->db->query($this->getSql());
        while(!$rs->EOF){
            $rs->fields['link'] = $rs->fields['child_num'] ? Core::getUrl('','',array('kw_pid'=>$rs->fields['id']),true) : null;
            $rs->fields['levOffset'] = $rs->fields['lev'] - $this->getNodeLevel();
            $list[] = $rs->fields;
            $rs->MoveNext();
        }
        return $list;
    }

    
    function sequence($s){
        foreach($s as $key=>$val){
            $sql = "update {$this->tab} set sequence={$val} where id={$key}";
            $this->db->Execute($sql);
        }
        if(!class_exists('Treenode')) include_once(LIB_DIR.'/treenode.class.php');
        $node = new Treenode($this->tab, $this->db);
        $node->buildTree(1,1);
    }

    
    function filter(){
        $filter = array();
        $filter['kw_pid'] = is_numeric($this->input['kw_pid']) ? $this->input['kw_pid'] : 1;
        $filter['kw_expan'] = $this->input['kw_expan'] ? $this->input['kw_expan'] : null;
        //Session
        $this->sess->setQueryData($filter);
        return $filter;
    }

    
    function getSql(){
        return $this->filter['kw_expan']
                ? "select c1.id as id, c1.title_zh as title_zh, c1.title_en as title_en, c1.lev as lev, c1.child_num as child_num, c1.tpl_l as tpl_l, c1.tpl_v as tpl_v, c1.description as description, c1.sequence as sequence from {$this->tab} c1, {$this->tab} c2 where c1.lft between c2.lft and c2.rgt and c2.id={$this->filter['kw_pid']} and c1.id <>1 and c1.id <> {$this->filter['kw_pid']} order by c1.lft"
                : "select * from {$this->tab} where pid={$this->filter['kw_pid']} order by lft";
    }

    
    function getNodeLevel(){
        return 1 + $this->db->GetOne("select lev from {$this->tab} where id={$this->filter['kw_pid']}");
    }

    
    function getNodePath() {
        $path = array();
        $sql = "select c2.id as id, c2.title_zh as title_zh from {$this->tab} c1, {$this->tab} c2 where c1.lft between c2.lft and c2.rgt and c1.id={$this->filter['kw_pid']} order by c2.lft asc";
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

    
    function getJsonData(){
        $expan = $this->filter['kw_expan'] ? false : true;
        return array(
            //¼
            'expanLink' => array(
                'url' => Core::getUrl('', '', array('kw_expan'=>$expan), true),
            ),
            //¼
            'createLink' => array(
                'url' => Core::getUrl('create'),
            ),
            'editLink' => array(
                'url' => Core::getUrl('edit'),
            ),
            'deleteLink' => array(
                'msg' => $this->lang->get('j_systemCategory_deleteItemMsg'), 
                'url' => Core::getUrl('delete'),
            ),
        );
    }
}
?>
