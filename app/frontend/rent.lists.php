<?php
include_once(LIB_DIR.'/category.class.php');
class Lists extends Page{
	var $AuthLevel = ACT_NEED_LOGIN;
    var $db;
    var $row = 20;
    var $currentPage = 0;
    var $filter;
    var $tab = 'fang';
    var $tab_user = 'member';

    
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
		//vardump($pvar);
        //设定模板使用变量
        $this->assign('v', stripQuotes($pvar));
        //显示页面
        $this->display();
    }

    
    function getData(){
        //sql查询语句
        $sqlQuery = "select * from {$this->tab} ";
        $sqlWhere = $this->getSqlWhere();
        $sqlOrder = " order by put_time desc";
        //保存查询数据到$list
        $list = array();
        $rs = $this->db->SelectLimit($sqlQuery.$sqlWhere.$sqlOrder, $this->row, $this->row * $this->currentPage);
        if(!$rs->RecordCount() && $this->currentPage !=0){
            $this->currentPage = 0;
            $rs = $this->db->SelectLimit($sqlQuery.$sqlWhere.$sqlOrder,$this->row,$this->row * $this->currentPage);
        }
        while(!$rs->EOF){
            $rs->fields['is_order'] = $rs->fields['is_order']?1:0;
            $rs->fields['put_time'] = date('Y-m-d H:i:s',$rs->fields['put_time']);
            $rs->fields['viewLink'] = Core::getUrl('view','esfang',array('id'=>$rs->fields['id']),'',true);
            $rs->fields['editLink'] = Core::getUrl('edit','',array('id'=>$rs->fields['id']),'',true);
            $rs->fields['delLink'] = Core::getUrl('state','esfang',array('id'=>$rs->fields['id'],'value'=>2),'',true);
			if($rs->fields['sn_images']){
				$rs->fields['sn_images'] =  explode(',',$rs->fields['sn_images']);
			}
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
        if(strlen($this->input['fytype']))        $filter['fytype'] = $this->input['fytype'];
        //保存过滤器到Session
        $this->sess->setQueryData($filter);
        return $filter;
    }

    
    function getSqlWhere(){
        $sqlWhere = " where m_id={$this->sess->get('memberid')} and ftype=2 ";
        $sqlWhere .= isset($this->filter['kw_state'])  ? " and state = {$this->filter['kw_state']}" : " and state <> 2 ";
		$sqlWhere .= isset($this->filter['fytype'])  ? " and fytype = '{$this->filter['fytype']}'" : ' and fytype = 243';
        return $sqlWhere;
    }

    
    function getTotal($sqlWhere){
        return $this->db->GetOne("select count(*) as total from {$this->tab} {$sqlWhere}");
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
