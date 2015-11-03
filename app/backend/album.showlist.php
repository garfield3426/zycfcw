<?php
/***************************************************************
 * 显示相册列表
 * 
 * @author maorenqi 
 ***************************************************************/
include_once(LIB_DIR.'/category.class.php');
class ShowList extends Page{
    var $db;
    var $row = 10;
    var $currentPage = 0;
    var $filter;
    var $tab = 'album';
    var $tab_photo = 'album_photo';

    /**
     * 构造方法
     */
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_photo = $this->conf->get('table_prefix').$this->tab_photo;
        
        $this->db = getDb();
        //$this->db->debug = true;
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
            //json格式的数据
            'jsonData' => $this->getJsonData(),
            //页面标题
            'title' => $this->lang->get('p_album_showlistTitle'),
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
        $sqlQuery = "select i.* from {$this->tab} i 
        	left join {$this->tab_photo} p on (p.album_id=i.id) ";
        $sqlWhere = $this->getSqlWhere();
        $sqlOrder = " group by i.title order by i.is_order desc,i.id desc";
        //保存查询数据到$list
        $list = array();
        $rs = $this->db->SelectLimit($sqlQuery.$sqlWhere.$sqlOrder, $this->row, $this->row * $this->currentPage);
        if(!$rs->RecordCount() && $this->currentPage !=0){
            $this->currentPage = 0;
            $rs = $this->db->SelectLimit($sql,$this->row,$this->row * $this->currentPage);
        }

        $albumId = array();
        while(!$rs->EOF){
            $rs->fields['put_time'] = date($this->conf->get('dateFormat') ,$rs->fields['put_time']);
            $rs->fields['viewLink'] = 'admin-album-edit-id-'.$rs->fields['id'].'.html';
            $rs->fields['editLink'] = 'admin-photo-add-album_id-'.$rs->fields['id'].'.html';
            $rs->fields['photoLink'] = 'admin-photo-showlist-album_id-'.$rs->fields['id'].'.html';
            $rs->fields['cate_id'] = Category::getTitle($rs->fields['cate_id']);
            $list[]=$rs->fields;
            $albumId[]=$rs->fields['id'];
            $rs->MoveNext();
        }

        $count = $this->getCountList($albumId);
        foreach($list as &$i){
            $i['counts'] = $count[$i['id']] ? $count[$i['id']] :0;
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
        if(is_numeric($this->input['kw_state']))    $filter['kw_state'] = $this->input['kw_state'];
        if(is_numeric($this->input['kw_cate']))     $filter['kw_cate'] = $this->input['kw_cate'];
        if(strlen($this->input['kw_title']))        $filter['kw_title'] = $this->input['kw_title'];
    
        //保存过滤器到Session
        $this->sess->setQueryData($filter);
        return $filter;
    }

    /**
     * 获得sql过滤语句
     * @return string
     */
    function getSqlWhere(){
        $sqlWhere = " where 1";
        $sqlWhere .= isset($this->filter['kw_state'])  ? " and i.state = {$this->filter['kw_state']}" : " and i.state <> 2";
        $sqlWhere .= isset($this->filter['kw_title'])  ? " and i.title like('%{$this->filter['kw_title']}%')" : '';
        $sqlWhere .= isset($this->filter['kw_cate'])   ? " and i.cate_id in (".implode(Category::getAllChild($this->filter['kw_cate']),',').")":'';
     
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
     * 统计各id的记录数量
     */
    function getCountList($albumId){
        if( !count($albumId) ) return array();
        $albumIdStr = implode(',',$albumId);
        $sql = "select count(*) as counts,album_id from $this->tab_photo where album_id in ($albumIdStr) and state<>2 group by album_id";
        $list = $this->db->getAll($sql);
        $return = array();
        foreach($list as $i){
            $return[$i['album_id']] = $i['counts'];
        }
       
        return $return;
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
            //类别选择器
            'kw_cate' => Category::getSelList(
                $item['kw_cate'],
                $this->conf->get('albumCateId'),
                false,
                '',
                false,
                $this->lang->get('j_global_cateSelDisableMsg')
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
                    'Enable' => $this->lang->get('p_album_mulopEnable'),
                    'Disable' => $this->lang->get('p_album_mulopDisable'),
                    'disabled_1' => '------',
                    'Delete' => $this->lang->get('p_album_mulopDelete'),
                ),
                'action' => array(
                    'type' => 'mulopChoose',
                    'Enable' => array(
                        'msg' => $this->lang->get('j_album_mulopEnable'),
                        'url' => Core::getUrl('state', '', array('value'=>1)),
                    ),
                    'Disable' => array(
                        'msg' => $this->lang->get('j_album_mulopDisable'),
                        'url' => Core::getUrl('state', '', array('value'=>0)),
                    ),
                    'Delete' => array(
                        'msg' => $this->lang->get('j_album_mulopDelete'),
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
                'msg' => $this->lang->get('j_album_deleteItemMsg'), 
                'url' => Core::getUrl('state', '', array('value'=>2)),
            ),
            'editLink' => array(
                'url' => Core::getUrl('edit'),
            )
            /*,
            'viewLink' => array(
                'url' => Core::getUrl('showlist','album_photo'),
            ),
            */
        );
    }
}
?>
