<?php

class Export extends Page{
    var $AuthLevel = ACT_NEED_LOGIN;
    var $db;
    var $row = 20;
    var $currentPage = 0;
    var $filter;
    var $tab = "member";
    var $tab_group = "mb_group";

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_group = $this->conf->get('table_prefix').$this->tab_group;
        $this->filter = $this->filter();

        $this->db = getDb();
        //$this->db->debug = true;
    }

    
    function process(){
        $sqlQuery = "select m.*, g.title as groupTitle from {$this->tab} m left join {$this->tab_group} g on m.gid=g.id";
        $sqlWhere = $this->getSqlWhere($this->filter);
        $sqlOrder = " order by m.reg_time desc, m.id desc";

        header("Content-Type:text/plain; charset=utf-8");
        header('Content-Disposition: attachment; filename="会员资料.txt"');

        echo "ID\t登录名\t真实姓名\t状态\t电子邮箱\t移动电话\t详细地址\t注册IP\t注册时间\r\n";
        $rs = $this->db->Execute($sqlQuery.$sqlWhere.$sqlOrder);
        while(!$rs->EOF){
            $rs->fields['reg_time'] = date('Y-m-d',$rs->fields['reg_time']);
            $rs->fields['area'] = $this->lang->get('global_areaList', $rs->fields['area']);
            $rs->fields['state'] = $rs->fields['state'] ? '已激活': '未激活';
            $r = $rs->fields;
            echo $r['id'] . "\t" . $r['username'] . "\t" . $r['name'] . "\t" . $r['state'] . "\t" . $r['email'] . "\t" . $r['mobile'] . "\t" . $r['addr'] . "\t" . $r['reg_ip'] . "\t" . $r['reg_time']. "\r\n";

            $rs->MoveNext();
        }
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
        $sqlWhere .= isset($this->filter['kw_bTime'])    ? " and m.reg_time > ".ext('unixtime',$this->filter['kw_bTime'],'0:0:0') : '';
        $sqlWhere .= isset($this->filter['kw_eTime'])    ? " and m.reg_time <= ".ext('unixtime',$this->filter['kw_eTime'],'23:59:59') : '';
        return $sqlWhere;
    }
}
?>
