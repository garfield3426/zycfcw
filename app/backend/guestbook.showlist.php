<?php

class ShowList extends Page{
    var $db;
    var $row = 5;
    var $currentPage = 0;
    var $filter;
    var $tab = 'guestbook';

    
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
            'title' => $this->lang->get('p_guestbook_showlistTitle'),
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
        while(!$rs->EOF){
            $rs->fields['put_time'] = date('Y-m-d',$rs->fields['put_time']);
            $rs->fields['reply_time'] = date('Y-m-d',$rs->fields['reply_time']);
            //处理性别
            $rs->fields['sex'] = $rs->fields['sex'] ? $this->lang->get('global_sexAsMan') : $this->lang->get('global_sexAsFemale');
            //处理地区
            //$rs->fields['area'] = $this->lang->get('global_areaList', $rs->fields['area']);
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
        if(is_numeric($this->input['kw_reply']))    $filter['kw_reply'] = $this->input['kw_reply'];
        if(strlen($this->input['kw_lang']))         $filter['kw_lang'] = $this->input['kw_lang'];
        if(strlen($this->input['kw_area']))         $filter['kw_area'] = $this->input['kw_area'];
        if(strlen($this->input['kw_title']))        $filter['kw_title'] = $this->input['kw_title'];
        if(ext('is_date',$this->input['kw_bTime'])) $filter['kw_bTime'] = $this->input['kw_bTime'];
        if(ext('is_date',$this->input['kw_eTime'])) $filter['kw_eTime'] = $this->input['kw_eTime'];
        //保存过滤器到Session
        $this->sess->setQueryData($filter);
        return $filter;
    }

    
    function getSqlWhere(){
        $sqlWhere = " where 1 and state <> 2";
        //$sqlWhere .= isset($this->filter['kw_state'])  ? " and state = {$this->filter['kw_state']}" : " and state <> 2";
        $sqlWhere .= isset($this->filter['kw_area'])  ? " and area = '{$this->filter['kw_area']}'" :'';
        $sqlWhere .= isset($this->filter['kw_lang'])   ? " and lang = '{$this->filter['kw_lang']}'" : '';
        $sqlWhere .= isset($this->filter['kw_title'])  ? " and title like('%{$this->filter['kw_title']}%')" : '';
        $sqlWhere .= isset($this->filter['kw_bTime'])  ? " and put_time > ".ext('unixtime',$this->filter['kw_bTime'],'0:0:0') : '';
        $sqlWhere .= isset($this->filter['kw_eTime'])  ? " and put_time <= ".ext('unixtime',$this->filter['kw_eTime'],'23:59:59') : '';
        $sqlWhere .= isset($this->filter['kw_reply'])  ? ($this->filter['kw_reply'] ? " and reply_time<>0" : " and reply_time is null") : '';
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
                ),
            ),
            //回复状态选择器
            'kw_reply' => array(
                'value' => $this->filter['kw_reply'],
                'list' => array(
                    '0' => $this->lang->get('p_guestbook_NotReply'),
                    '1' => $this->lang->get('p_guestbook_IsReply'),
                ),
            ),
            //语言选择器
            'kw_lang' => array(
                'value' => $this->filter['kw_lang'],
                'list' => $this->lang->getSelectList(),
            ),
            //地区选择器
            'kw_area' => array(
                'value' => $this->filter['area'],
                'list' => $this->lang->get('global_areaList'),
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
                    'Enable' => $this->lang->get('p_guestbook_mulopEnable'),
                    'Disable' => $this->lang->get('p_guestbook_mulopDisable'),
                    'disabled_1' => '------',
                    'Delete' => $this->lang->get('p_guestbook_mulopDelete'),
                ),
                'action' => array(
                    'type' => 'mulopChoose',
                    'Enable' => array(
                        'msg' => $this->lang->get('j_guestbook_mulopEnable'),
                        'url' => Core::getUrl('state', '', array('value'=>1)),
                    ),
                    'Disable' => array(
                        'msg' => $this->lang->get('j_guestbook_mulopDisable'),
                        'url' => Core::getUrl('state', '', array('value'=>0)),
                    ),
                    'Delete' => array(
                        'msg' => $this->lang->get('j_guestbook_mulopDelete'),
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
                'msg' => $this->lang->get('j_guestbook_deleteItemMsg'), 
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
