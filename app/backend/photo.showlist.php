<?php
/***************************************************************
 * 显示相片列表
 * 
 * @author maorenqi 
 ***************************************************************/

class ShowList extends Page{
    var $db;
    var $row = 20;
    var $currentPage = 0;
    var $filter;
    var $album_id;
    var $tab = 'album_photo';
    var $tab_album = 'album';
    //var $tab_member = 'member';

    /**
     * 构造方法
     */
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_album = $this->conf->get('table_prefix').$this->tab_album;
        //$this->tab_member = $this->conf->get('table_prefix').$this->tab_member;
        $this->db = getDb();
        //$this->db->debug = true;
        $this->row = (5 <= $this->input['row'] && 100 >= $this->input['row']) ? (int)$this->input['row'] : $this->row;
        $this->currentPage = is_numeric($this->input['page']) ? (int)$this->input['page'] : $this->currentPage;
        //$this->filter = $this->filter();
    }

    /**
     * 程序入口
     */
    function process(){
    	if($this->input[album_id]){
    		$this->album_id = $this->input[album_id];
    	}else{
    		phpBox('参数错误！');
    		gotoPage(h);
    		exit;
    	}
        $pvar = array(
            //获得过滤器
            'kw' => $this->filter,
            //获得输出列表
            'list' => $this->getData(),
            //json格式的数据
            'jsonData' => $this->getJsonData(),
            //页面标题
            'title' => $this->lang->get('p_photoshowlistTitle'),
            //form Action
            'formAct' => Core::getUrl('', '', array('page'=>0), true),
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
        $sqlQuery = "select i.*, a.title as album_title from {$this->tab} i 
        	left join $this->tab_album a on a.id=i.album_id";
        	
        $sqlWhere = $this->getSqlWhere();
        $sqlOrder = " order by i.is_order DESC,i.id DESC";
        //保存查询数据到$list
        $list = array();
        $rs = $this->db->SelectLimit($sqlQuery.$sqlWhere.$sqlOrder, $this->row, $this->row * $this->currentPage);
        if(!$rs->RecordCount() && $this->currentPage !=0){
            $this->currentPage = 0;
            $rs = $this->db->SelectLimit($sql,$this->row,$this->row * $this->currentPage);
        }
        while(!$rs->EOF){
            $rs->fields['put_time'] = date($this->conf->get('dateFormat') ,$rs->fields['put_time']);
            //$rs->fields['path'] = preg_replace("/\/(.*)$/",'/thum/$1',$rs->fields['path']);
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
        /*if(is_numeric($this->input['kw_state']))  $filter['kw_state'] = $this->input['kw_state'];
        if(strlen($this->input['kw_title']))        $filter['kw_title'] = $this->input['kw_title'];
        if(strlen($this->input['kw_username']))     $filter['kw_username'] = $this->input['kw_username'];
        if(ext('is_date',$this->input['kw_bTime'])) $filter['kw_bTime'] = $this->input['kw_bTime'];
        if(ext('is_date',$this->input['kw_eTime'])) $filter['kw_eTime'] = $this->input['kw_eTime'];*/
        //保存过滤器到Session
        $this->sess->setQueryData($filter);
        return $filter;
    }

    /**
     * 获得sql过滤语句
     * @return string
     */
    function getSqlWhere(){
        $sqlWhere = " where i.album_id = {$this->album_id} and i.state<>2";
        $sqlWhere .= isset($this->filter['kw_state'])  ? " and i.state = {$this->filter['kw_state']}" : " and i.state <> 2";
        $sqlWhere .= isset($this->filter['kw_title'])  ? " and i.title like('%{$this->filter['kw_title']}%')" : '';
        /*$sqlWhere .= isset($this->filter['kw_username']) ? " and m.username like('%".$this->filter['kw_username']."%')" : '';
        $sqlWhere .= isset($this->filter['kw_bTime'])  ? " and i.put_time > ".ext('unixtime',$this->filter['kw_bTime'],'0:0:0') : '';
        $sqlWhere .= isset($this->filter['kw_eTime'])  ? " and i.put_time <= ".ext('unixtime',$this->filter['kw_eTime'],'23:59:59') : '';*/
        return $sqlWhere;
    }

    /**
     * 获得统计信息.
     * @param string $sqlWhere
     * @return int
     */
    function getTotal($sqlWhere){
        return $this->db->GetOne("select count(*) as total from {$this->tab} i {$sqlWhere}");
    }

    /**
     * 获得需要JSON输出的数据
     * @return array
     */
    function getJsonData(){
        return array(
            //状态选择器
            'kw_state' => array(
                'value' => $this->filter['kw_state'],
                'list' => array(
                    '0' => $this->lang->get('p_album_mulopDisable'),
                    '1' => $this->lang->get('p_album_mulopEnable'),
                    //'2' => $this->lang->get('global_stateDelete'),
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
                    'Enable' => $this->lang->get('p_photo_mulopEnable'),
                    'Disable' => $this->lang->get('p_photo_mulopDisable'),
                    'disabled_1' => '------',
                    'Delete' => $this->lang->get('p_photo_mulopDelete'),
                ),
                'action' => array(
                    'type' => 'mulopChoose',
                    'Enable' => array(
                        'msg' => $this->lang->get('j_photo_mulopEnable'),
                        'url' => Core::getUrl('state', '', array('value'=>1)),
                    ),
                    'Disable' => array(
                        'msg' => $this->lang->get('j_photo_mulopDisable'),
                        'url' => Core::getUrl('state', '', array('value'=>0)),
                    ),
                    'Delete' => array(
                        'msg' => $this->lang->get('j_photo_mulopDelete'),
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
                'msg' => $this->lang->get('j_photo_deleteItemMsg'), 
                'url' => Core::getUrl('state', '', array('value'=>2)),
            ),
            'editLink' => array(
                'url' => Core::getUrl('edit'),
            ),
        );
    }
}
?>
