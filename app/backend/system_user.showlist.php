<?php

class ShowList extends Page{
    var $db;
    var $row = 20;
    var $currentPage = 0;
    var $filter;
    var $tab = "sys_user";
    var $tab_group = "sys_group";

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_group = $this->conf->get('table_prefix').$this->tab_group;
        $this->db = getDb();
        //$this->db->debug = true;
        $this->row = (5 <= $this->input['row'] && 100 >= $this->input['row']) ? (int)$this->input['row'] : $this->row;
        $this->currentPage = is_numeric($this->input['page']) ? (int)$this->input['page'] : $this->currentPage;
        $this->filter = $this->filter();
    }

    
    function process(){
        $pvar = array(
            'kw' => $this->filter,
            'list' => $this->getData(),
            'jsonData' => $this->getJsonData(),
            'title' => $this->lang->get('p_systemUser_showlistTitle'),
            'formAct' => Core::getUrl('', '', array('page'=>0), true),
        );

        //设定模板使用变量
        $this->assign('v', stripQuotes($pvar));
        //显示页面
        $this->display();
    }

    
    function getData(){
        $sqlQuery = 
            "select su.*, g.title as groupTitle
            from {$this->tab} su left join {$this->tab_group} g on su.gid=g.id";
        $sqlWhere = $this->getSqlWhere($this->filter);
        $sqlOrder = " order by su.reg_time desc, su.id desc";
        //保存查询数据到$list
        $list = array();
        $rs = $this->db->SelectLimit($sqlQuery.$sqlWhere.$sqlOrder, $this->row, $this->row * $this->currentPage);
        if(!$rs->RecordCount() && $this->currentPage !=0){
            $this->currentPage = 0;
            $rs = $this->db->SelectLimit($sql,$this->row,$this->row * $this->currentPage);
        }
             
        while(!$rs->EOF){
        	
            $rs->fields['reg_time'] = date('Y-m-d',$rs->fields['reg_time']);
            $list[]=$rs->fields;
            $rs->MoveNext();
        }
        return $list;
    }

    
    function filter(){
        $filter = array();
        if(strlen($this->input['kw_username'])) $filter['kw_username'] = $this->input['kw_username'];
        if(strlen($this->input['kw_email'])) $filter['kw_email'] = $this->input['kw_email'];
        if(is_numeric($this->input['kw_state'])) $filter['kw_state'] = $this->input['kw_state'];
        
        if(is_numeric($this->input['kw_group'])) $filter['kw_group'] = $this->input['kw_group'];
        if(ext('is_date',$this->input['kw_bTime'])) $filter['kw_bTime'] = $this->input['kw_bTime'];
        if(ext('is_date',$this->input['kw_eTime'])) $filter['kw_eTime'] = $this->input['kw_eTime'];
        //保存过滤器到Session
        $this->sess->setQueryData($filter);
        return $filter;
    }

    
    function getSqlWhere(){
        $sqlWhere = " where 1";
        $sqlWhere .= isset($this->filter['kw_state'])    ? " and su.state={$this->filter['kw_state']}" : " and su.state<>2";
        $sqlWhere .= isset($this->filter['kw_group'])    ? " and su.gid={$this->filter['kw_group']}" : '';
        
        $sqlWhere .= isset($this->filter['kw_username']) ? " and su.username like('%{$this->filter['kw_username']}%')" : '';
        $sqlWhere .= isset($this->filter['kw_email'])    ? " and su.email like('%{$this->filter['kw_email']}%')" : '';
        $sqlWhere .= isset($this->filter['kw_bTime'])    ? " and su.reg_time > ".ext('unixtime',$this->filter['kw_bTime'],'0:0:0') : '';
        $sqlWhere .= isset($this->filter['kw_eTime'])    ? " and su.reg_time <= ".ext('unixtime',$this->filter['kw_eTime'],'23:59:59') : '';
        return $sqlWhere;
    }

    
    function getTotal($sqlWhere){
        return $this->db->GetOne("select count(*) as total from {$this->tab} su left join {$this->tab_group} g on su.gid=g.id {$sqlWhere}");
    }

    
    function getJsonData(){
        return array(
            //状态选择器
            'kw_state' => array(
                'value' => $this->filter['kw_state'],
                'list' => array(
                    '0' => $this->lang->get('p_systemUser_stateDisabled'),
                    '1' => $this->lang->get('p_systemUser_stateEnabled'),
                    //'2' => $this->lang->get('p_systemUser_stateDelete'),
                ),
            ),
            //会员组选择器
            'kw_group' => array(
                'value' => $this->filter['kw_group'],
                'list' => $this->getGroupList(),
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
            //多选操作
            'mulop' => array(
                'display' => true,
                'list' => array(
                    'Enable' => $this->lang->get('p_systemUser_mulopEnable'),
                    'Disable' => $this->lang->get('p_systemUser_mulopDisable'),
                    'disabled_1' => '------',
                    'Delete' => $this->lang->get('p_systemUser_mulopDelete'),
                ),
                'action' => array(
                    'type' => 'mulopChoose',
                    'Enable' => array(
                        'msg' => $this->lang->get('j_systemUser_mulopEnable'),
                        'url' => Core::getUrl('state', '', array('value'=>1)),
                    ),
                    'Disable' => array(
                        'msg' => $this->lang->get('j_systemUser_mulopDisable'),
                        'url' => Core::getUrl('state', '', array('value'=>0)),
                    ),
                    'Delete' => array(
                        'msg' => $this->lang->get('j_systemUser_mulopDelete'),
                        'url' => Core::getUrl('state', '', array('value'=>2)),
                    ),
                ),
            ),
            //日期选择器
            'kw_bTime' => array(
                'value' => $this->filter['kw_bTime'],
            ),
            'kw_eTime' => array(
                'value' => $this->filter['kw_eTime'],
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
            'deleteLink' => array(
                'msg' => $this->lang->get('j_systemUser_deleteItemMsg'), 
                'url' => Core::getUrl('state', '', array('value'=>2)),
            ),
            'editLink' => array(
                'url' => Core::getUrl('edit'),
            ),
        );
    }

    
    function getGroupList(){
        $list = $this->db->GetAll("select id, title from {$this->tab_group}");
        $result = array();
        foreach($list as $i) $result[$i['id']] = $i['title'];
        return $result;
    }
}
?>
