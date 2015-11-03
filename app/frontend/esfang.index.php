<?php
/***************************************************************
 * 显示网上求医信息
 * 
 * @author maorenqi 
 ***************************************************************/
//引入分页处理的类
include_once(LIB_DIR.'/pager.class.php');

class Index extends Page{
    var $AuthLevel = ACT_OPEN;
    var $db;
    var $row = 5;
    var $currentPage = 0;
    var $filter;
    var $tab = 'client';
   // var $tab_user = 'sys_user';

    /**
     * 构造方法
     */
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        $this->row = (5 <= $this->input['row'] && 100 >= $this->input['row']) ? (int)$this->input['row'] : $this->row;
        $this->currentPage = is_numeric($this->input['page']) ? (int)$this->input['page'] : $this->currentPage;
        $this->filter = $this->filter();
    }

    /**
     * 程序入口
     */
    function process(){
       $pvar = array(
            //获得过滤器
            'kw' => $this->filter,
            //获得输出列表
            'list' => $this->getData(),
            //form Action
            'formAct' => Core::getUrl('showlist', 'article', array('page'=>0), true,true),
            //手术类型
            'client_type' => unserialize(CLIENT_TYPE),
            //Pager
            'pager' => Pager::index($this->getTotal($this->getSqlWhere()), $this->currentPage, $this->row),
        );
        //设定模板使用变量
        $this->assign('v', stripQuotes($pvar));
        //显示页面
        $this->display();
    }

    /**
     * 查询数据
     * @return array
     */
    function getData(){
        //sql查询语句
        $sqlQuery =
            "SELECT * FROM {$this->tab}";
        $sqlWhere = $this->getSqlWhere();
        $sqlOrder = " ORDER BY id DESC";
        //保存查询数据到$list
        $list = array();
        $rs = $this->db->SelectLimit($sqlQuery.$sqlWhere.$sqlOrder, $this->row, $this->row * $this->currentPage);
        if(!$rs->RecordCount() && $this->currentPage !=0){
            $this->currentPage = 0;
            $rs = $this->db->SelectLimit($sql,$this->row,$this->row * $this->currentPage);
        }
        while(!$rs->EOF){
        	$rs->fields['put_time'] = date('Y.m.d', $rs->fields['put_time']);
            $rs->fields['viewLink'] = Core::getUrl('view', '', array('id'=>$rs->fields['id']),'',true);
           	$list[]=$rs->fields;
            $rs->MoveNext();
        }
       
        return $list;
    }

    /**
     * 获得过滤器信息
     * @param array $input
     * @return array
     */
    function filter(){
        $filter = array();
        if(is_numeric($this->input['row']))         $filter['row'] = $this->input['row'];
        if(is_numeric($this->input['page']))        $filter['page'] = $this->input['page'];
        if(strlen($this->input['name']))        $filter['name'] = $this->input['name'];
        if(strlen($this->input['type']))        $filter['type'] = $this->input['type'];
        
        //保存过滤器到Session
        $this->sess->setQueryData($filter);
        return $filter;
    }

    /**
     * 获得sql过滤语句
     * @return string
     */
    function getSqlWhere(){
        $sqlWhere = " WHERE state = 1";
        $sqlWhere .= isset($this->filter['name'])  ? " AND name LIKE('%{$this->filter['name']}%')" : '';
        $sqlWhere .= isset($this->filter['type'])  ? " AND types={$this->filter['type']}" : '';
        
        return $sqlWhere;
    }

    /**
     * 获得统计信息.
     * @param string $sqlWhere
     * @return int
     */
    function getTotal($sqlWhere){
        return $this->db->GetOne("SELECT count(*) AS total FROM {$this->tab} {$sqlWhere}");
    }
}
?>
