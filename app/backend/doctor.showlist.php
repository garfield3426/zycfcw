<?php


//include_once(LIB_DIR.'/category.class.php');
class ShowList extends Page{
    var $db;
    var $row = 20;
    var $currentPage = 0;
    var $filter;
    var $tab = 'doctor';
    var $tab_user = 'sys_user';
    var $tab_branch = 'branch';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_user = $this->conf->get('table_prefix').$this->tab_user;
        $this->tab_branch = $this->conf->get('table_prefix').$this->tab_branch;
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
            'hospital' =>  getAllHospital(),
            'branch' =>  $this->getBranch(),
            //获得输出列表
            'list' => $this->getData(),
            //json格式的数据
            'jsonData' => $this->getJsonData(),
            //页面标题
            'title' => $this->lang->get('p_doctor_showlistTitle'),
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
        $sqlQuery =
            "select i.id as id,i.h_id as h_id, i.state as state, i.name as name, i.img, i.duty, i.rank, i.is_order, u.username as editor, b.name as b_name
            from {$this->tab} i left join {$this->tab_user} u on i.editor=u.id left join {$this->tab_branch} b on i.b_id=b.id ";
        $sqlWhere = $this->getSqlWhere();
        $sqlOrder = " order by i.is_order DESC,i.id DESC";
        //保存查询数据到$list
        $list = array();
        $rs = $this->db->SelectLimit($sqlQuery.$sqlWhere.$sqlOrder, $this->row, $this->row * $this->currentPage);
        if(!$rs->RecordCount() && $this->currentPage !=0){
            $this->currentPage = 0;
            $rs = $this->db->SelectLimit($sqlQuery.$sqlWhere.$sqlOrder,$this->row,$this->row * $this->currentPage);
        }
        while(!$rs->EOF){
        	$rs->fields['hospital_name'] = getHospitalName($rs->fields['h_id']);
            $list[]=$rs->fields;
            $rs->MoveNext();
        }
        return $list;
    }

    
    function filter(){
        $filter = array();
        if(is_numeric($this->input['row']))          $filter['row'] = $this->input['row'];
        if(is_numeric($this->input['page']))         $filter['page'] = $this->input['page'];
        if(is_numeric($this->input['kw_state']))     $filter['kw_state'] = $this->input['kw_state'];
        if(is_numeric($this->input['kw_h_id']))      $filter['kw_h_id'] = $this->input['kw_h_id'];
        if(is_numeric($this->input['kw_b_id']))      $filter['kw_b_id'] = $this->input['kw_b_id'];
        if(strlen($this->input['kw_duty']))          $filter['kw_duty'] = $this->input['kw_duty'];
        if(strlen($this->input['kw_rank']))          $filter['kw_rank'] = $this->input['kw_rank'];
        if(strlen($this->input['kw_title']))         $filter['kw_title'] = $this->input['kw_title'];
        if(strlen($this->input['kw_editor']))        $filter['kw_editor'] = $this->input['kw_editor'];
        //保存过滤器到Session
        $this->sess->setQueryData($filter);
        return $filter;
    }

    
    function getSqlWhere(){
        $sqlWhere = " where 1";
        $sqlWhere .= isset($this->filter['kw_state'])  ? " and i.state = {$this->filter['kw_state']}" 				: " and i.state <> 2";
        $sqlWhere .= isset($this->filter['kw_h_id'])   ? " and i.h_id = {$this->filter['kw_h_id']}" 				: '';
        $sqlWhere .= isset($this->filter['kw_b_id'])   ? " and i.b_id = {$this->filter['kw_b_id']}" 				: '';
        $sqlWhere .= isset($this->filter['kw_duty'])   ? " and i.duty like('%{$this->filter['kw_duty']}%')" 		: '';
        $sqlWhere .= isset($this->filter['kw_rank'])   ? " and i.rank like('%{$this->filter['kw_rank']}%')" 		: '';
        $sqlWhere .= isset($this->filter['kw_title'])  ? " and i.name like('%{$this->filter['kw_title']}%')" 		: '';
        $sqlWhere .= isset($this->filter['kw_editor']) ? " and u.username like('%".$this->filter['kw_editor']."%')" : '';
        
        return $sqlWhere;
    }

    function getTotal($sqlWhere){
        return $this->db->GetOne("select count(*) as total from {$this->tab} i left join {$this->tab_user} u on i.editor=u.id  left join {$this->tab_branch} b on i.b_id=b.id  {$sqlWhere}");
    }

    function getBranch(){
        $sql = "select * from {$this->tab_branch} where state=1 ";
        $row = $this->db->GetAll($sql);
        return $row;
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
                    'Enable' => $this->lang->get('p_doctor_mulopEnable'),
                    'Disable' => $this->lang->get('p_doctor_mulopDisable'),
                    'disabled_1' => '------',
                    'Delete' => $this->lang->get('p_doctor_mulopDelete'),
                ),
                'action' => array(
                    'type' => 'mulopChoose',
                    'Enable' => array(
                        'msg' => $this->lang->get('j_doctor_mulopEnable'),
                        'url' => Core::getUrl('state', '', array('value'=>1)),
                    ),
                    'Disable' => array(
                        'msg' => $this->lang->get('j_doctor_mulopDisable'),
                        'url' => Core::getUrl('state', '', array('value'=>0)),
                    ),
                    'Delete' => array(
                        'msg' => $this->lang->get('j_doctor_mulopDelete'),
                        'url' => Core::getUrl('state', '', array('value'=>2)),
                    ),
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
            'deleteLink' => array(
                'msg' => $this->lang->get('j_doctor_deleteItemMsg'), 
                'url' => Core::getUrl('state', '', array('value'=>2)),
            ),
            'editLink' => array(
                'url' => Core::getUrl('edit'),
            ),
        );
    }
}
?>
