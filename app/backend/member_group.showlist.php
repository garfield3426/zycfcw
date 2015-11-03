<?php

class ShowList extends Page{
    var $db;
    var $row = 20;
    var $currentPage = 0;
    var $filter;
    var $tab = 'mb_group';
    var $tab_user = 'member';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_user = $this->conf->get('table_prefix').$this->tab_user;
        $this->db = getDb();
        //$this->db->debug = true;
        $this->row = (5 <= $this->input['row'] && 100 >= $this->input['row']) ? (int)$this->input['row'] : $this->row;
        $this->currentPage = is_numeric($this->input['page']) ? (int)$this->input['page'] : $this->currentPage;
        $this->filter = $this->filter();
    }

    
    function process(){
        $pvar = array(
            //获得过滤器
            'kw' => $this->filter,
            //获得输出列表
            'list' => $this->getData(),
            //json格式的数据
            'jsonData' => $this->getJsonData(),
            //页面标题
            'title' => $this->lang->get('p_memberGroup_showlistTitle'),
        );
        //设定模板使用变量
        $this->assign('v', stripQuotes($pvar));
        //显示页面
        $this->display();
    }

    
    function getData(){
        //sql查询语句
        $sqlQuery = "select * from {$this->tab}";
        $sqlWhere = $this->getSqlWhere();
        $sqlOrder = " order by id desc";
        //保存查询数据到$list
        $list = array();
        $rs = $this->db->SelectLimit($sqlQuery.$sqlWhere.$sqlOrder, $this->row, $this->row * $this->currentPage);
        if(!$rs->RecordCount() && $this->currentPage !=0){
            $this->currentPage = 0;
            $rs = $this->db->SelectLimit($sql,$this->row,$this->row * $this->currentPage);
        }
        while(!$rs->EOF){
            $rs->fields['userTotal'] = $this->getUserTotal($rs->fields['id']);
            $list[]=$rs->fields;
            $rs->MoveNext();
        }
        return $list;
    }


    
    function filter(){
        $filter = array();
        if(is_numeric($this->input['row']))   $filter['row'] = $this->input['row'];
        if(is_numeric($this->input['page']))  $filter['page'] = $this->input['page'];
        if(is_numeric($this->input['kw_state'])) $filter['kw_state'] = $this->input['kw_state'];
        if(strlen($this->input['kw_title']))  $filter['kw_title'] = $this->input['kw_title'];
        //保存过滤器到Session
        $this->sess->setQueryData($filter);
        return $filter;
    }

    
    function getSqlWhere(){
        $sqlWhere = " where 1";
        $sqlWhere .= isset($this->filter['kw_state'])  ? " and state={$this->filter['kw_state']}" : " and state<>2";
        $sqlWhere .= isset($this->filter['kw_title'])  ? " and title like('%{$this->filter['kw_title']}%')" : '';
        return $sqlWhere;
    }

    
    function getTotal($sqlWhere){
        return $this->db->GetOne("select count(*) as total from {$this->tab} {$sqlWhere}");
    }

    
    function getUserTotal($gid){
        return $this->db->GetOne("select count(*) as total from {$this->tab_user} where gid={$gid}");
    }

    
    function getJsonData(){
        return array(
            //状态选择器
            'kw_state' => array(
                'value' => $this->filter['kw_state'],
                'list' => array(
                    '0' => $this->lang->get('p_memberGroup_stateDisabled'),
                    '1' => $this->lang->get('p_memberGroup_stateEnabled'),
                    //'2' => $this->lang->get('p_memberGroup_stateDelete'),
                ),
            ),
            //多选操作
            'mulop' => array(
                'display' => true,
                'list' => array(
                    'Enable' => $this->lang->get('p_memberGroup_mulopEnable'),
                    'Disable' => $this->lang->get('p_memberGroup_mulopDisable'),
                    'disabled_1' => '------',
                    'Delete' => $this->lang->get('p_memberGroup_mulopDelete'),
                ),
                'action' => array(
                    'type' => 'mulopChoose',
                    'Enable' => array(
                        'msg' => $this->lang->get('j_memberGroup_mulopEnable'),
                        'url' => Core::getUrl('state', '', array('value'=>1)),
                    ),
                    'Disable' => array(
                        'msg' => $this->lang->get('j_memberGroup_mulopDisable'),
                        'url' => Core::getUrl('state', '', array('value'=>0)),
                    ),
                    'Delete' => array(
                        'msg' => $this->lang->get('j_memberGroup_mulopDelete'),
                        'url' => Core::getUrl('state', '', array('value'=>2)),
                    ),
                ),
            ),
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
            //分页按钮
            'pagination' => array_merge(
                $this->lang->get('pagination'),
                array(
                    'total' => $this->getTotal($this->getSqlWhere()),
                    'row' => $this->row,
                    'currentPage' => $this->currentPage,
                    'url' => Core::getUrl('', '', '', true)
                )
            ),
            //操作链接事件
            'permissionsLink' => array(
                'url' => Core::getUrl('permissions'),
            ),
            'editLink' => array(
                'url' => Core::getUrl('edit'),
            ),
            'deleteLink' => array(
                'msg' => $this->lang->get('j_memberGroup_deleteItemMsg'), 
                'url' => Core::getUrl('state', '', array('value'=>2)),
            ),
        );
    }
}
?>
