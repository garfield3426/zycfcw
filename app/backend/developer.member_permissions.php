<?php

class Member_Permissions extends Page {
    var $AuthLevel = ACT_NEED_LOGIN;
    var $db;
    var $tab = "mb_permissions";
    var $tab_priv = "mb_group_priv";

    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_priv = $this->conf->get('table_prefix').$this->tab_priv;
        $this->db = getDb();
        //$this->db->debug = true;
    }
    function process(){
        switch($this->input['do']){
        case 'add':
            $this->add();
            break;
        case 'update':
            $this->update();
            break;
        case 'del':
            $this->del();
            break;
        default:
            $this->permList();
        }
    }
    function permList(){
        $this->input = trimArr($this->input);
        $currentPage = is_numeric($this->input['page'])?$this->input['page']:0;
        $row = (is_numeric($this->input['row']) && $this->input['row'] <=100)?$this->input['row']:20;
        $sqlWhere = " where 1";
        if(strlen($this->input['kw_title'])){
            $pvar['kw']['kw_title'] = $this->input['kw_title'];
            $sqlWhere .= " and title like('%{$this->input['kw_title']}%')";
        }
        $sqlOrder = " order by title";
        $sql = "select * from {$this->tab}";
        $rs = $this->db->SelectLimit($sql.$sqlWhere.$sqlOrder, $row, $row * $currentPage);
        $pvar['list'] = array();
        if(!$rs->RecordCount() && $currentPage !=0){
            //将第一页的记录显示出来
            $currentPage = 0;
            $rs = $this->db->SelectLimit($sql.$sqlWhere, $row, $row * $currentPage);
        }
        while(!$rs->EOF){
            $pvar['list'][] = $rs->fields;
            $rs->MoveNext();
        }
        $pvar['kw']['row'] = $row;
        $pvar['kw']['page'] = $currentPage;
        $this->sess->setQueryData($pvar['kw']);
        if ($rs->RecordCount()) {
            $totalRecord = $this->db->GetOne(preg_replace('|^SELECT.*FROM|i', 'SELECT COUNT(*) as total FROM', $sql.$sqlWhere));
        }
        $pvar['title'] = $this->lang->get('p_developer_PremissionsTitle');
        $pvar['formAct'] = Core::getUrl('','','',true);
        $pvar['jsonData'] = $this->getJsonData();
        $pvar['jsonData']['pagination'] = array_merge(
            $this->lang->get('pagination'),
            array(
                'total' => $totalRecord,
                'row' => $row,
                'currentPage' => $currentPage,
                'url' => Core::getUrl('', '', '', true)
            )
        );
        $this->addTplFile('premissions_list');
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }
    function add(){
        $this->input = trimArr($this->input);
        $data = $this->input['item'];
        if(empty($this->input['submit'])){
            $this->showForm($this->input['do'],$data);
            return;
        }
        if($emsg = $this->validate($data)){
            $this->showForm($this->input['do'],$data,$emsg);
            return;
        }
        unset($data['id']);
        $sql = "select * from {$this->tab} where id = -1";
        $rs = $this->db->Execute($sql);
        $sql = $this->db->GetInsertSQL($rs, $data);
        if(!$this->db->Execute($sql)){
            Core::raiseMsg($this->lang->get('e_developer_invalidationManipulation'));
        }
        Core::redirect(Core::getUrl('','','',true));
    }
    function update(){
        $data = $this->input['item'];
        $id = $this->input['id']?$this->input['id']:$data['id'];
        if(!is_numeric($id)){
            Core::raiseMsg($this->lang->get('e_developer_PremissionsIdIsEmpty'));
        }
        if(empty($this->input['submit'])){
            $data = $this->getPermData($id);
            $this->showForm($this->input['do'],$data);
            return;
        }
        if($emsg = $this->validate($data)){
            $this->showForm($this->input['do'],$data,$emsg);
            return;
        }
        $sql = "select * from {$this->tab} where id = $id";
        $rs = $this->db->Execute($sql);
        $sql = $this->db->GetUpdateSQL($rs, $data);
        $this->db->Execute($sql);		
        Core::redirect(Core::getUrl('','','',true));
    }
    function del(){
        $id = $this->input['id'];
        if(is_array($id)){
            $id = implode(',',$id);	
        }
        else if (!is_numeric($id)) {
            Core::raiseMsg($this->lang->get('e_developer_PremissionsIdIsEmpty'));
        }
        $sql = 'delete from '.$this->tab." where id in($id)";
        $this->db->Execute($sql);

        $sql = "delete from {$this->tab_priv} where action_id in({$id})";
        $this->db->Execute($sql);

        Core::redirect(Core::getUrl('','','',true));
    }
    function getPermData($id){
        $sql = "select * from {$this->tab} where id=$id";
        return $this->db->GetRow($sql);
    }
    function showForm($do, $data, $emsg=null){
        if('update' == $do){
            $title = $this->lang->get('p_developer_PremissionsUpdateTitie');
            $formAct = Core::getUrl('','',array('do' => $do));
        }else {
            $title = $this->lang->get('p_developer_PremissionsTitle');
            $formAct = Core::getUrl('','',array('do' => $do));
        }
        $pvar['title'] = $title;
        $pvar['item'] = $data;
        $pvar['jsonData']['emsg'] = $emsg;
        $pvar['jsonData']['goBackLink'] = array('url' => Core::getUrl('','','',true));
        $pvar['formAct'] = $formAct;

        $this->addTplFile('premissions_form');
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }
    function validate($data){
        $e = array();
        if(!strlen($data['title'])){
            $e['title'] = $this->lang->get('e_developer_PremissionsTitleEmpty');
        }elseif($this->isExist($data['title'],$data['id'])){
            $e['title'] = $this->lang->get('e_developer_PremissionsTitleIsExist');
        }
        if(!strlen($data['description'])){
            $e['description'] = $this->lang->get('e_developer_PremissionsDescriptionIsEmpty');
        }
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
    function isExist($title,$me = null){
        $sql = "select id from {$this->tab} where title='$title'";
        $id = $this->db->GetOne($sql);
        if(!strlen($id)) return false;
        else if ($me == $id) return false;
        else return true;
    }

    //add by Try 2008-03-28
    function getJsonData(){
        return array(
            //行数选择器
            'row' => array(
                'display' => true,
                'value' => $this->row,
                'list' => array(
                    '20' => $this->lang->get('global_row20'),
                    '50' => $this->lang->get('global_row50'),
                    '100' => $this->lang->get('global_row100'),
                ),
                'action' => array(
                    'type' => 'rowsChoose',
                    'url' => Core::getUrl('', '', array('page'=>0), true),
                ),
            ),
            //多选操作
            'mulop' => array(
                'display' => true,
                'list' => array(
                    'Delete' => $this->lang->get('p_developer_PremissionsMulopDelete'),
                ),
                'action' => array(
                    'type' => 'mulopChoose',
                    'Delete' => array(
                        'msg' => $this->lang->get('j_developer_PremissionsMulopDelete'),
                        'url' => Core::getUrl('', '', array('do'=>'del')),
                    ),
                ),
            ),
            //操作链接事件
            'addLink' => array(
                'msg' => $this->lang->get('j_developer_PremissionsDeleteItemMsg'), 
                'url' => Core::getUrl('','',array('do'=>'add')),
            ),
            'editLink' => array(
                'msg' => $this->lang->get('j_developer_PremissionsEditItemMsg'),
                'url' => Core::getUrl('','',array('do'=>'update','id'=>$id)),
            ),
            'deleteLink' => array(
                'msg' => $this->lang->get('j_article_deleteItemMsg'), 
                'url' => Core::getUrl('', '', array('do'=>'del')),
            ),
        );
    }
}
?>
