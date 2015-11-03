<?php
include_once(LIB_DIR.'/category.class.php');
class Lists extends Page{
	var $AuthLevel = ACT_OPEN;
    var $db;
    var $row = 20;
    var $currentPage = 0;
    var $filter;
    var $tab = 'article';
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
            'title' => $this->lang->get('p_article_showlistTitle'),
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
            "select i.id as id, i.state as state, i.is_order as is_order,i.browse as browse, i.color as color, i.cate_id as cate_id, i.title as title,i.recommend as recommend,i.hot as hot, i.put_time as put_time,i.edit_time as edit_time,i.editor1 as editor1, u.username as editor
            from {$this->tab} i left join {$this->tab_user} u on i.editor=u.id";
        $sqlWhere = $this->getSqlWhere();
        $sqlOrder = " order by i.put_time desc";
        //保存查询数据到$list
        $list = array();
        $rs = $this->db->SelectLimit($sqlQuery.$sqlWhere.$sqlOrder, $this->row, $this->row * $this->currentPage);
        if(!$rs->RecordCount() && $this->currentPage !=0){
            $this->currentPage = 0;
            $rs = $this->db->SelectLimit($sqlQuery.$sqlWhere.$sqlOrder,$this->row,$this->row * $this->currentPage);
        }
        while(!$rs->EOF){
            $rs->fields['cate_id'] = Category::getTitle($rs->fields['cate_id']);
            $rs->fields['is_order'] = $rs->fields['is_order']?1:0;
            $rs->fields['put_time'] = date('Y-m-d H:i:s',$rs->fields['put_time']);
            $rs->fields['edit_time'] = date('Y-m-d H:i:s',$rs->fields['edit_time']);
            $rs->fields['viewLink'] = Core::getUrl('view','article',array('id'=>$rs->fields['id']),'',true);
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
        if(strlen($this->input['kw_order']))    	$filter['kw_order'] = $this->input['kw_order'];
        if(strlen($this->input['kw_color']))        $filter['kw_color'] = $this->input['kw_color'];
        if(strlen($this->input['kw_title']))        $filter['kw_title'] = $this->input['kw_title'];
        if(is_numeric($this->input['kw_cate']))     $filter['kw_cate'] = $this->input['kw_cate'];
        if(strlen($this->input['kw_editor']))       $filter['kw_editor'] = $this->input['kw_editor'];
        if(ext('is_date',$this->input['kw_bTime'])) $filter['kw_bTime'] = $this->input['kw_bTime'];
        if(ext('is_date',$this->input['kw_eTime'])) $filter['kw_eTime'] = $this->input['kw_eTime'];
        //保存过滤器到Session
        $this->sess->setQueryData($filter);
        return $filter;
    }

    
    function getSqlWhere(){
        $sqlWhere = " where 1";
        $sqlWhere .= isset($this->filter['kw_state'])  ? " and i.state = {$this->filter['kw_state']}" : " and i.state <> 2";
        if($this->filter['kw_order']==1){
        	$sqlWhere .= " and i.is_order<>0";
        }else if($this->filter['kw_order']==2){
        	$sqlWhere .= " and i.is_order=0";
        }else {
        	$sqlWhere .= '';
        }      
        $sqlWhere .= isset($this->filter['kw_color'])  ? " and i.color = '{$this->filter['kw_color']}'" : '';
        $sqlWhere .= isset($this->filter['kw_title'])  ? " and i.title like('%{$this->filter['kw_title']}%')" : '';
        $sqlWhere .= isset($this->filter['kw_editor']) ? " and u.username like('%".$this->filter['kw_editor']."%')" : '';
        $sqlWhere .= isset($this->filter['kw_bTime'])  ? " and i.put_time > ".ext('unixtime',$this->filter['kw_bTime'],'0:0:0') : '';
        $sqlWhere .= isset($this->filter['kw_eTime'])  ? " and i.put_time <= ".ext('unixtime',$this->filter['kw_eTime'],'23:59:59') : '';
        $cateId = isset($this->filter['kw_cate']) ? $this->filter['kw_cate'] : $this->conf->get('articleCateId');
       
        $sqlWhere .= " and i.cate_id in (".implode(Category::getAllChild($cateId),',').")";
      
        return $sqlWhere;
    }

    
    function getTotal($sqlWhere){
        return $this->db->GetOne("select count(*) as total from {$this->tab} i left join {$this->tab_user} u on i.editor=u.id {$sqlWhere}");
    }

    
    function getJsonData(){
        return array(
            
            //置顶
            'kw_order' => array(
                'value' => $this->filter['kw_order'],
                'list' => array(
                    '1' => $this->lang->get('global_orderEnabled'),
                    '2' => $this->lang->get('global_orderDisabled'),
                   
                ),
            ),
            //套红
            'kw_color' => array(
                'value' => $this->filter['kw_color'],
                'list' => array( 
                   '1' => $this->lang->get('global_colorEnabled'),
                   '0' => $this->lang->get('global_colorDisabled'),
                ),
            ),
            //状态选择器
            'kw_state' => array(
                'value' => $this->filter['kw_state'],
                'list' => array(
                    '0' => $this->lang->get('global_stateDisabled'),
                    '1' => $this->lang->get('global_stateEnabled'),
                    //'2' => $this->lang->get('global_stateDelete'),
                ),
            ),
            //类别选择器
            'kw_cate' => Category::getSelList(
                $item['kw_cate'],
                $this->conf->get('articleCateId'),
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
                    'Enable' => $this->lang->get('p_article_mulopEnable'),
                    'Disable' => $this->lang->get('p_article_mulopDisable'),
                    'disabled_1' => '------',
                    'Delete' => $this->lang->get('p_article_mulopDelete'),
                ),
                'action' => array(
                    'type' => 'mulopChoose',
                    'Enable' => array(
                        'msg' => $this->lang->get('j_article_mulopEnable'),
                        'url' => Core::getUrl('state', '', array('value'=>1)),
                    ),
                    'Disable' => array(
                        'msg' => $this->lang->get('j_article_mulopDisable'),
                        'url' => Core::getUrl('state', '', array('value'=>0)),
                    ),
                    'Delete' => array(
                        'msg' => $this->lang->get('j_article_mulopDelete'),
                        'url' => Core::getUrl('state', '', array('value'=>2)),
                    ),
                ),
            ),
            //日期选择器
            'kw_bTime' => array(
                'value' => $this->input['kw_bTime'],
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
                'msg' => $this->lang->get('j_article_deleteItemMsg'), 
                'url' => Core::getUrl('state', '', array('value'=>2)),
            ),
            'editLink' => array(
                'url' => Core::getUrl('edit'),
            ),
        );
    }
}
?>
