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
    var $tab_zuozhen = 'doctor_zuozhen';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_user = $this->conf->get('table_prefix').$this->tab_user;
        $this->tab_branch = $this->conf->get('table_prefix').$this->tab_branch;
        $this->tab_zuozhen = $this->conf->get('table_prefix').$this->tab_zuozhen;
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
            'branch' =>  $this->getBranch(),
            //获得输出列表
            'list' => $this->getData(),
            //获得当前周一周的日期
            'nonceWeek' => $this->getweekday(),
            //获得下一周的日期
            'getNextWeekDay' => $this->getNextWeekDay(),
            //json格式的数据
            'jsonData' => $this->getJsonData(),
            //页面标题
            'title' => $this->lang->get('p_zuozhen_showlistTitle'),
            //form Action
            'formAct' => Core::getUrl('save', '', array(), true),
        );
        //设定模板使用变量
        $this->assign('v', stripQuotes($pvar));
        //显示页面
        $this->display();
    }

    
    function getData(){
        //sql查询语句
        $sqlQuery =
            "select i.id as id, i.state as state, i.b_id as b_id, i.name as name, i.img, i.duty, i.rank, i.is_order, u.username as editor, b.name as b_name
            from {$this->tab} i left join {$this->tab_user} u on i.editor=u.id left join {$this->tab_branch} b on i.b_id=b.id ";
        $sqlWhere = $this->getSqlWhere();
        $sqlOrder = " order by i.is_order";
        //保存查询数据到$list
        $list = array();
        $rs = $this->db->SelectLimit($sqlQuery.$sqlWhere.$sqlOrder, $this->row, $this->row * $this->currentPage);
        if(!$rs->RecordCount() && $this->currentPage !=0){
            $this->currentPage = 0;
            $rs = $this->db->SelectLimit($sqlQuery.$sqlWhere.$sqlOrder,$this->row,$this->row * $this->currentPage);
        }
        
        while(!$rs->EOF){
        	$result=array();
        	$sql="select id,zuozhen_date,amorpm from {$this->tab_zuozhen} where state=1 and doctor_id=".$rs->fields['id']." and h_id=".CONTENT_SKY;
        	$result=$this->db->GetAll($sql);
        	$rs->fields['result'] = $result;
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
        if(is_numeric($this->input['kw_branch']))    $filter['kw_branch'] = $this->input['kw_branch'];
        if(strlen($this->input['kw_duty']))          $filter['kw_duty'] = $this->input['kw_duty'];
        if(strlen($this->input['kw_rank']))          $filter['kw_rank'] = $this->input['kw_rank'];
        if(strlen($this->input['kw_title']))         $filter['kw_title'] = $this->input['kw_title'];
        if(strlen($this->input['kw_editor']))        $filter['kw_editor'] = $this->input['kw_editor'];
        //保存过滤器到Session
        $this->sess->setQueryData($filter);
        return $filter;
    }

    
    function getSqlWhere(){
        $sqlWhere = " where i.h_id=".CONTENT_SKY;
        $sqlWhere .= isset($this->filter['kw_state'])  ? " and i.state = {$this->filter['kw_state']}" : " and i.state <> 2";
        $sqlWhere .= isset($this->filter['kw_branch'])  ? " and i.b_id = {$this->filter['kw_branch']}" : '';
        $sqlWhere .= isset($this->filter['kw_duty'])  ? " and i.duty like('%{$this->filter['kw_duty']}%')" : '';
        $sqlWhere .= isset($this->filter['kw_rank'])  ? " and i.rank like('%{$this->filter['kw_rank']}%')" : '';
        $sqlWhere .= isset($this->filter['kw_title'])  ? " and i.name like('%{$this->filter['kw_title']}%')" : '';
        $sqlWhere .= isset($this->filter['kw_editor']) ? " and u.username like('%".$this->filter['kw_editor']."%')" : '';
        return $sqlWhere;
    }

    
    function getTotal($sqlWhere){
        return $this->db->GetOne("select count(*) as total from {$this->tab} i left join {$this->tab_user} u on i.editor=u.id  left join {$this->tab_branch} b on i.b_id=b.id  {$sqlWhere}");
    }

    
    function getBranch(){
        $sql = "select * from {$this->tab_branch} where state=1 and h_id=".CONTENT_SKY;
        $row = $this->db->GetAll($sql);
        return $row;
    }

    
    function getweekday(){
		$weekArr = array();
		$dayOfWeek = date("w");
		for($i = 0; $i < 8; $i++)
		{
			$weekArr[$i] = mktime(0, 0, 0, date("m"), date("d") - $dayOfWeek + $i, date("Y")); // 得到时间戳格式
			
		}
		
		return $weekArr;
	}
	
	
	function getNextWeekDay(){
		$weekArr = array();
		$dayOfWeek = date("w");
		for($i = 0; $i < 8; $i++)
		{
			//得到一周中每一天的时间戳
			$weekDadyUTime = mktime(0, 0, 0, date("m") ,date("d") - $dayOfWeek + $i, date("Y"));
			$weekArr[$i] = $weekDadyUTime + (7 * 24 * 60 * 60);  // 得到时间戳格式
		}
		return $weekArr;
	}
	
	// 计算两个日期差几天
	function play_days($sday,$eday)
	{
		$d1 = strtotime($sday);
		$d2 = strtotime($eday);
		$days = round(($d2-$d1)/3600/24) + 1;
		return $days;
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
