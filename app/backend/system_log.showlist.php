<?php

class ShowList extends Page{
    var $db;
    var $row = 20;
    var $currentPage = 0;
    var $filter;
    var $tab = 'log';
    var $tab_user = 'sys_user';

    
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
            'title' => $this->lang->get('p_systemLog_showlistTitle'),
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
        $sqlQuery = "select l.*, u.username as `userid` from {$this->tab} l left join {$this->tab_user} u on l.userid=u.id";
        $sqlWhere = $this->getSqlWhere();
        $sqlOrder = " order by l.id desc";
        //保存查询数据到$list
        $list = array();
        $rs = $this->db->SelectLimit($sqlQuery.$sqlWhere.$sqlOrder, $this->row, $this->row * $this->currentPage);
        if(!$rs->RecordCount() && $this->currentPage !=0){
            $this->currentPage = 0;
            $rs = $this->db->SelectLimit($sql,$this->row,$this->row * $this->currentPage);
        }
        $levelStr = array(
            'NOTICE' => $this->lang->get('p_systemLog_levelNotice'),
            'DEBUG' => $this->lang->get('p_systemLog_levelDebug'),
            'WARNING' => $this->lang->get('p_systemLog_levelWarning'),
            'ERROR' => $this->lang->get('p_systemLog_levelError'),
            'DATABASE' => $this->lang->get('p_systemLog_levelDatabase'),
        );
        while(!$rs->EOF){
            $rs->fields['put_time'] = date('Y-m-d H:i:s',$rs->fields['put_time']);
            $rs->fields['lev'] = $levelStr[$rs->fields['lev']];
            $list[]=$rs->fields;
            $rs->MoveNext();
        }
        return $list;
    }

    
    function filter(){
        $filter = array();
        if(is_numeric($this->input['row']))         $filter['row'] = $this->input['row'];
        if(is_numeric($this->input['page']))        $filter['page'] = $this->input['page'];
        if(strlen($this->input['kw_level']))        $filter['kw_level'] = $this->input['kw_level'];
        if(strlen($this->input['kw_user']))         $filter['kw_user'] = $this->input['kw_user'];
        if(strlen($this->input['kw_appid']))        $filter['kw_appid'] = $this->input['kw_appid'];
        if(strlen($this->input['kw_module']))       $filter['kw_module'] = $this->input['kw_module'];
        if(strlen($this->input['kw_action']))       $filter['kw_action'] = $this->input['kw_action'];
        if(strlen($this->input['kw_msg']))          $filter['kw_msg'] = $this->input['kw_msg'];
        if(ext('is_date',$this->input['kw_bTime'])) $filter['kw_bTime'] = $this->input['kw_bTime'];
        if(ext('is_date',$this->input['kw_eTime'])) $filter['kw_eTime'] = $this->input['kw_eTime'];
        //保存过滤器到Session
        $this->sess->setQueryData($filter);
        return $filter;
    }

    
    function getSqlWhere(){
        $sqlWhere = " where 1";
        $sqlWhere .= isset($this->filter['kw_level'])  ? " and l.lev = '{$this->filter['kw_level']}'" : '';
        $sqlWhere .= isset($this->filter['kw_user'])   ? " and u.username like('%{$this->filter['kw_user']}%')" : '';
        $sqlWhere .= isset($this->filter['kw_appid'])  ? " and l.app like('%{$this->filter['kw_appid']}%')" : '';
        $sqlWhere .= isset($this->filter['kw_module']) ? " and l.module like('%{$this->filter['kw_module']}%')" : '';
        $sqlWhere .= isset($this->filter['kw_action']) ? " and l.act like('%{$this->filter['kw_action']}%')" : '';
        $sqlWhere .= isset($this->filter['kw_msg'])    ? " and l.msg like('%{$this->filter['kw_msg']}%')" : '';
        $sqlWhere .= isset($this->filter['kw_bTime'])  ? " and l.put_time > ".ext('unixtime',$this->filter['kw_bTime'],'0:0:0') : '';
        $sqlWhere .= isset($this->filter['kw_eTime'])  ? " and l.put_time <= ".ext('unixtime',$this->filter['kw_eTime'],'23:59:59') : '';
        return $sqlWhere;
    }

    
    function getTotal($sqlWhere){
        return $this->db->GetOne("select count(*) as total from {$this->tab} l left join {$this->tab_user} u on l.userid=u.id {$sqlWhere}");
    }

    
    function getJsonData(){
        return array(
            //状态选择器
            'kw_level' => array(
                'value' => $this->filter['kw_level'],
                'list' => array(
                    'NOTICE' => $this->lang->get('p_systemLog_levelNotice'),
                    'DEBUG' => $this->lang->get('p_systemLog_levelDebug'),
                    'WARNING' => $this->lang->get('p_systemLog_levelWarning'),
                    'ERROR' => $this->lang->get('p_systemLog_levelError'),
                    'DATABASE' => $this->lang->get('p_systemLog_levelDatabase'),
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
            //多选操作
            'mulop' => array(
                'display' => true,
                'list' => array(
                    'Delete' => $this->lang->get('p_systemLog_mulopDelete'),
                ),
                'action' => array(
                    'type' => 'mulopChoose',
                    'Delete' => array(
                        'msg' => $this->lang->get('j_systemLog_mulopDelete'),
                        'url' => Core::getUrl('delete'),
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
                'msg' => $this->lang->get('j_systemLog_deleteItemMsg'), 
                'url' => Core::getUrl('delete'),
            ),
            'clearLink' => array(
                'msg' => $this->lang->get('j_systemLog_clearMsg'),
                'url' => Core::getUrl('delete','', array('id'=>all)),
            ),
        );
    }
}
?>
