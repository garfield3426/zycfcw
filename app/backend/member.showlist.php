<?php

class ShowList extends Page{
    var $db;
    var $row = 20;
    var $currentPage = 0;
    var $filter;
    var $tab = "member";
    //var $tab_group = "mb_group";

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        //$this->tab_group = $this->conf->get('table_prefix').$this->tab_group;
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
            'title' => $this->lang->get('p_member_showlistTitle'),
            'formAct' => Core::getUrl('', '', array('page'=>0), true),
        );
        //设定模板使用变量
        $this->assign('v', stripQuotes($pvar));
        //显示页面
        $this->display();
    }

    
    function getData(){
        $sqlQuery = 
            "select m.* from {$this->tab} m ";
        $sqlWhere = $this->getSqlWhere($this->filter);
        $sqlOrder = " order by m.regdate desc, m.id desc";
        //保存查询数据到$list
        $list = array();
        $rs = $this->db->SelectLimit($sqlQuery.$sqlWhere.$sqlOrder, $this->row, $this->row * $this->currentPage);
        if(!$rs->RecordCount() && $this->currentPage !=0){
            $this->currentPage = 0;
            $rs = $this->db->SelectLimit($sql,$this->row,$this->row * $this->currentPage);
        }
        while(!$rs->EOF){
            $rs->fields['regdate'] = date('Y-m-d',$rs->fields['regdate']);
            $rs->fields['lastdate'] = date('Y-m-d',$rs->fields['lastdate']);
            $rs->fields['area'] = $this->lang->get('global_areaList', $rs->fields['area']);
            $list[]=$rs->fields;
            $rs->MoveNext();
        }
        return $list;
    }

    
    function filter(){
        $filter = array();
        if(strlen($this->input['kw_username']))     $filter['kw_username'] = $this->input['kw_username'];
        if(strlen($this->input['kw_email']))        $filter['kw_email'] = $this->input['kw_email'];
        if(strlen($this->input['kw_zip']))          $filter['kw_zip'] = $this->input['kw_zip'];
        if(strlen($this->input['kw_area']))         $filter['kw_area'] = $this->input['kw_area'];
        if(is_numeric($this->input['kw_state']))    $filter['kw_state'] = $this->input['kw_state'];
        if(is_numeric($this->input['kw_group']))    $filter['kw_group'] = $this->input['kw_group'];
        if(ext('is_date',$this->input['kw_bTime'])) $filter['kw_bTime'] = $this->input['kw_bTime'];
        if(ext('is_date',$this->input['kw_eTime'])) $filter['kw_eTime'] = $this->input['kw_eTime'];
        //保存过滤器到Session
        $this->sess->setQueryData($filter);
        return $filter;
    }

    
    function getSqlWhere(){
        $sqlWhere = " where 1";
        $sqlWhere .= isset($this->filter['kw_state'])    ? " and m.state={$this->filter['kw_state']}" : " and m.state<>2";
        $sqlWhere .= isset($this->filter['kw_group'])    ? " and m.gid={$this->filter['kw_group']}" : '';
        $sqlWhere .= isset($this->filter['kw_username']) ? " and m.username like('%{$this->filter['kw_username']}%')" : '';
        $sqlWhere .= isset($this->filter['kw_email'])    ? " and m.email like('%{$this->filter['kw_email']}%')" : '';
        $sqlWhere .= isset($this->filter['kw_area'])     ? " and m.area='{$this->filter['kw_area']}'" : '';
        $sqlWhere .= isset($this->filter['kw_zip'])      ? " and m.zip like('%{$this->filter['kw_zip']}%')" : '';
        $sqlWhere .= isset($this->filter['kw_bTime'])    ? " and m.regdate > ".ext('unixtime',$this->filter['kw_bTime'],'0:0:0') : '';
        $sqlWhere .= isset($this->filter['kw_eTime'])    ? " and m.regdate <= ".ext('unixtime',$this->filter['kw_eTime'],'23:59:59') : '';
        return $sqlWhere;
    }

    
    function getTotal($sqlWhere){
        return $this->db->GetOne("select count(*) as total from {$this->tab} m  {$sqlWhere}");
    }

    
    function getJsonData(){
        return array(
            //状态选择器
            'kw_state' => array(
                'display' => true,
                'value' => $this->filter['kw_state'],
                'list' => array(
                    '0' => $this->lang->get('p_member_stateDisabled'),
                    '1' => $this->lang->get('p_member_stateEnabled'),
                    //'2' => $this->lang->get('p_member_stateDelete'),
                ),
            ),
            //会员组选择器
            /* 'kw_group' => array(
                'value' => $this->filter['kw_group'],
                'list' => $this->getGroupList(),
            ), */
            //地区选择器
            'kw_area' => array(
                'value' => $this->filter['kw_area'],
                'list' => $this->lang->get('global_areaList'),
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
                    'Enable' => $this->lang->get('p_member_mulopEnable'),
                    'Disable' => $this->lang->get('p_member_mulopDisable'),
                    'disabled_1' => '------',
                    'Delete' => $this->lang->get('p_member_mulopDelete'),
                ),
                'action' => array(
                    'type' => 'mulopChoose',
                    'Enable' => array(
                        'msg' => $this->lang->get('j_member_mulopEnable'),
                        'url' => Core::getUrl('state', '', array('value'=>1)),
                    ),
                    'Disable' => array(
                        'msg' => $this->lang->get('j_member_mulopDisable'),
                        'url' => Core::getUrl('state', '', array('value'=>0)),
                    ),
                    'Delete' => array(
                        'msg' => $this->lang->get('j_member_mulopDelete'),
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
                'msg' => $this->lang->get('j_member_deleteItemMsg'), 
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
