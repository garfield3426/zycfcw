<?php

class ShowList extends Page{
    var $db;
    var $row = 20;
    var $currentPage = 0;
    var $filter;
    var $tab = 'comment';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
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
            'title' => $this->lang->get('p_bespeak_showlistTitle'),
            //form Action
            'formAct' => Core::getUrl('', '', array('page'=>0), true),
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
       $type = unserialize(BESPEAK_TYPE);
        while(!$rs->EOF){
            $rs->fields['put_time'] = date('Y-m-d',$rs->fields['put_time']);
           
            $list[]=$rs->fields;
            $rs->MoveNext();
        }

        return $list;
    }


    
    function filter(){
        $filter = array();
        if(is_numeric($this->input['row']))         $filter['row'] = $this->input['row'];
        if(is_numeric($this->input['page']))        $filter['page'] = $this->input['page'];
        if(is_numeric($this->input['kw_state']))    $filter['kw_state'] = $this->input['kw_state'];
        if(is_numeric($this->input['kw_id']))    	$filter['kw_id'] = $this->input['kw_id'];
        if($this->input['kw_name'])    				$filter['kw_name'] = $this->input['kw_name'];
        if(ext('is_date',$this->input['kw_bTime'])) $filter['kw_bTime'] = $this->input['kw_bTime'];
        if(ext('is_date',$this->input['kw_eTime'])) $filter['kw_eTime'] = $this->input['kw_eTime'];
        //保存过滤器到Session
        $this->sess->setQueryData($filter);
        return $filter;
    }

    
    function getSqlWhere(){
        $sqlWhere = " where 1 and state <> 2";
        $sqlWhere .= isset($this->filter['kw_id'])  ? " and article_id = {$this->filter['kw_id']}" : '';
        $sqlWhere .= isset($this->filter['kw_name'])  ? " and name like('%{$this->filter['kw_name']}%')" : ''; 
        $sqlWhere .= isset($this->filter['kw_bTime'])  ? " and put_time > ".ext('unixtime',$this->filter['kw_bTime'],'0:0:0') : '';
        $sqlWhere .= isset($this->filter['kw_eTime'])  ? " and put_time <= ".ext('unixtime',$this->filter['kw_eTime'],'23:59:59') : '';

        return $sqlWhere;
    }

    
    function getTotal($sqlWhere){
        return $this->db->GetOne("select count(*) as total from {$this->tab}{$sqlWhere}");
    }

    
    function getJsonData(){
        return array(
            //状态选择器
            'kw_state' => array(
                'value' => $this->filter['kw_state'],
                'list' => array(
                    '0' => $this->lang->get('global_stateDisabled'),
                    '1' => $this->lang->get('global_stateEnabled'),
                    //'2' => $this->lang->get('global_stateDelete'),
                ),
            ),
            //回复状态选择器
            'kw_reply' => array(
                'value' => $this->filter['kw_reply'],
                'list' => array(
                    '0' => $this->lang->get('p_bespeak_NotReply'),
                    '1' => $this->lang->get('p_bespeak_IsReply'),
                ),
            ),
            
            //行数选择器
            'row' => array(
                'value' => $this->row,
                'list' => array(
                    '5' => $this->lang->get('global_row5'),
                    '10' => $this->lang->get('global_row10'),
                    '50' => $this->lang->get('global_row50'),
                ),
                'action' => array(
                    'type' => 'rowsChoose',
                    'url' => Core::getUrl('', '', array('page'=>0), true),
                ),
            ),
            //多选操作
            'mulop' => array(
                'list' => array(
                    'Enable' => $this->lang->get('p_bespeak_mulopEnable'),
                    'Disable' => $this->lang->get('p_bespeak_mulopDisable'),
                    'disabled_1' => '------',
                    'Delete' => $this->lang->get('p_bespeak_mulopDelete'),
                ),
                'action' => array(
                    'type' => 'mulopChoose',
                    'Enable' => array(
                        'msg' => $this->lang->get('j_bespeak_mulopEnable'),
                        'url' => Core::getUrl('state', '', array('value'=>1)),
                    ),
                    'Disable' => array(
                        'msg' => $this->lang->get('j_bespeak_mulopDisable'),
                        'url' => Core::getUrl('state', '', array('value'=>0)),
                    ),
                    'Delete' => array(
                        'msg' => $this->lang->get('j_bespeak_mulopDelete'),
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
            //手术类型选择器
            'kw_types' => array(
                'value' => $item['types'],
                'list' => $this->lang->get('bespeak_type'),
            ),
            //操作链接事件
            'deleteLink' => array(
                'msg' => $this->lang->get('j_bespeak_deleteItemMsg'), 
                'url' => Core::getUrl('state', '', array('value'=>2)),
            ),
            'editLink' => array(
                'url' => Core::getUrl('edit'),
            ),
            'replyLink' => array(
                'url' => Core::getUrl('reply'),
            ),
        );
    }
}
?>
