<?php
/*查询条件:
*根据区域(qy)、总价（zj1、zj2）、户型（fx）、面积（mj）、特色（ts）、住宅类别（zzlb）、房龄（fl）、朝向（cx）、楼层（lc）、建筑类别（jzlb）、房屋结构（fwjg）、装修（zx）、配套（pt）、小学（xx）、初中（cz）、地铁（dt）、电轨（dg）
*URL:http://www.zycfcw.com/index-esfang-showlist-row-1-qy-1-zj1-100-zj2-200-fx-1-mj-300-ts-2-zzlb-3-fl-3-cx-4-lc-6-jzlb-6-fwjg-88-zx-77-pt-8-xx-8-cz-99-dt-999-dg-4.html(163个字节)
http://www.zycfcw.com/index-esfang-showlist-row-10-page-0-qy-120.html
*/
include_once(LIB_DIR.'/category.class.php');
//引入分页处理的类
include_once(LIB_DIR.'/pager.class.php');
class ShowList extends Page{
	var $AuthLevel = ACT_OPEN;
    var $db;
    var $row = 10;
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
		if(is_numeric($this->input['qy'])){
			$sq_pid = Category::getPidId($this->input['qy'],'商圈');
			if($sq_pid){
				$sq = Category::getListP($sq_pid);
			}
		}
		
		if(is_numeric($this->input['dt'])){
				$dt = Category::getListP($this->input['dt']);
		}
		
        $pvar = array(
		
            //获得过滤器
            'kw' => $this->filter,
            'lx_xzl' => $this->lang->get('lx_xzl'),
            'fl_qg_zz' => $this->lang->get('fl_qg_zz'),
            'lc_qg_zz' => $this->lang->get('lc_qg_zz'),
			'sq' =>  $sq,
            'dt' =>  $dt,
            //获得输出列表
            'list' => $this->getData(),
            //json格式的数据
            'jsonData' => $this->getJsonData(),
            //页面标题
            'title' => $this->lang->get('p_article_showlistTitle'),
            //form Action
            'formAct' => Core::getUrl('', '', array('page'=>0), true),
			 //Pager
            'pager' => Pager::index($this->getTotal($this->getSqlWhere()), $this->currentPage, $this->row),
        );
		//vardump($pvar[pager]totalPage);
        //设定模板使用变量
        $this->assign('v', stripQuotes($pvar));
        //显示页面
        $this->display();
    }

    function getData(){
        //sql查询语句
        $sqlQuery =
            "select  i.*,u.id as mem_id,u.name as m_name from {$this->tab} i left join {$this->tab_user} u on i.m_id=u.id";
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
			if( $rs->fields['cx'] ){
				$rs->fields['cx'] = Category::getTitle($rs->fields['cx']);
			}
			if($rs->fields['sn_images']){
				$rs->fields['sn_images']= explode(',',$rs->fields['sn_images'] );
			}
            $rs->fields['is_order'] = $rs->fields['is_order']?1:0;
            $rs->fields['put_time'] = date('Y-m-d H:i:s',$rs->fields['put_time']);
            $rs->fields['edit_time'] = date('Y-m-d H:i:s',$rs->fields['edit_time']);
            $rs->fields['viewLink'] = Core::getUrl('view','',array('id'=>$rs->fields['id']),'',true);
            $list[]=$rs->fields;
            $rs->MoveNext();
        }
        return $list;
    }

   
    function filter(){
        $filter = array();
        if(is_numeric($this->input['row']))         $filter['row'] = $this->input['row'];
        if(is_numeric($this->input['page']))        $filter['page'] = $this->input['page'];
        if(is_numeric($this->input['qy']))          $filter['qy'] = $this->input['qy'];//区域
		if(is_numeric($this->input['sq']))          $filter['sq'] = $this->input['sq'];//商圈
        if(is_numeric($this->input['zj']))          $filter['zj'] = $this->input['zj'];//总价
        if(is_numeric($this->input['zjl']))         $filter['zjl'] = $this->input['zjl'];//总价输入低区间
        if(is_numeric($this->input['zjh']))         $filter['zjh'] = $this->input['zjh'];//总价输入高区间
        if(is_numeric($this->input['hx']))          $filter['hx'] = $this->input['hx'];//户型
        if(is_numeric($this->input['fx']))          $filter['fx'] = $this->input['fx'];
        if(is_numeric($this->input['mj']))          $filter['mj'] = $this->input['mj'];//面积
        if(is_numeric($this->input['mjl']))          $filter['mjl'] = $this->input['mjl'];//面积输入低区间输入高区间
        if(is_numeric($this->input['mjh']))          $filter['mjh'] = $this->input['mjh'];//面积
        if(is_numeric($this->input['ts']))          $filter['ts'] = $this->input['ts'];//特色
        if(is_numeric($this->input['zzlb']))        $filter['zzlb'] = $this->input['zzlb'];
        if(is_numeric($this->input['fl']))          $filter['fl'] = $this->input['fl'];//房龄
        if(is_numeric($this->input['cx']))          $filter['cx'] = $this->input['cx'];//朝向
        if(is_numeric($this->input['lc']))          $filter['lc'] = $this->input['lc'];//楼层
        if(is_numeric($this->input['jzlb']))        $filter['jzlb'] = $this->input['jzlb'];
        if(is_numeric($this->input['fwjg']))        $filter['fwjg'] = $this->input['fwjg'];
        if(is_numeric($this->input['zx']))          $filter['zx'] = $this->input['zx'];
        if(is_numeric($this->input['pt']))          $filter['pt'] = $this->input['pt'];
        if(is_numeric($this->input['xx']))          $filter['xx'] = $this->input['xx'];//学校
        if(is_numeric($this->input['cz']))          $filter['cz'] = $this->input['cz'];
        if(is_numeric($this->input['dt']))          $filter['dt'] = $this->input['dt'];//地铁
        if(is_numeric($this->input['dtxl']))        $filter['dtxl'] = $this->input['dtxl'];//地铁线路
        if(is_numeric($this->input['dg']))          $filter['dg'] = $this->input['dg'];//电轨
		if(is_numeric($this->input['lx']))          $filter['lx'] = $this->input['lx'];//写字楼类型
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
        $sqlWhere = " where  i.ftype=2 and i.fytype=245 and i.state=1";
       // $sqlWhere .= isset($this->filter['kw_state'])  ? " and i.state = {$this->filter['kw_state']}" : " and i.state <> 2";
        if($this->filter['kw_order']==1){
        	$sqlWhere .= " and i.is_order<>0";
        }else if($this->filter['kw_order']==2){
        	$sqlWhere .= " and i.is_order=0";
        }else {
        	$sqlWhere .= '';
        }      
        $sqlWhere .= isset($this->filter['qy'])  ? " and i.qy = '{$this->filter['qy']}'" : '';//处理区域
        $sqlWhere .= isset($this->filter['sq'])  ? " and i.sq = '{$this->filter['sq']}'" : '';//处理区域
        $sqlWhere .= isset($this->filter['dt'])  ? " and i.dt = '{$this->filter['dt']}'" : '';//处理地铁
        $sqlWhere .= isset($this->filter['dtxl'])  ? " and i.dtxl = '{$this->filter['dtxl']}'" : '';//处理地铁
        $sqlWhere .= isset($this->filter['dg'])  ? " and i.dg = '{$this->filter['dg']}'" : '';//处理电轨
        $sqlWhere .= isset($this->filter['cx'])  ? " and i.cx = '{$this->filter['cx']}'" : '';//处理朝向
        //$sqlWhere .= isset($this->filter['fl'])  ? " and i.fl = '{$this->filter['fl']}'" : '';//处理房龄
        $sqlWhere .= isset($this->filter['zxcd'])  ? " and i.zxcd = '{$this->filter['zxcd']}'" : '';//处理装修程序
        $sqlWhere .= isset($this->filter['zzlb'])  ? " and i.zzlb = '{$this->filter['zzlb']}'" : '';//处理住宅类别
        $sqlWhere .= isset($this->filter['jzlx'])  ? " and i.jzlx = '{$this->filter['jzlx']}'" : '';//处理建筑类别
		$sqlWhere .= isset($this->filter['ts'])  ? " and i.ts like('%{$this->filter['ts']}%')" : '';//特色
		$sqlWhere .= isset($this->filter['lx'])  ? " and i.lx_xzl  = '{$this->filter['lx']}'" : '';//写字楼类型
		//处理总价
		if(isset($this->filter['zj'])){
			switch ($this->filter['zj']){
				case 1:
				 $sqlWhere .= " and i.zj<=50";
				  break;
				case 2:
				  $sqlWhere .= " and i.zj >=50 and i.zj <= 80";
				  break;
				case 3:
				  $sqlWhere .= " and i.zj >=80 and i.zj <= 100";
				  break;
				 case 4:
				  $sqlWhere .= " and i.zj >=100 and i.zj <= 150";
				  break;
				 case 5:
				  $sqlWhere .= " and i.zj >=150";
				  break;
				default:
				  echo "";
			}
		}
		//处理总价输入区间查询
		if(isset($this->filter['zjh'])){
			$zjl = isset($this->filter['zjl'])?$this->filter['zjl']:'0';
			$sqlWhere .= " and i.zj >={$zjl} and i.zj <=  '{$this->filter['zjh']}'";
		}
		//处理户型
		if(isset($this->filter['hx'])){
			if($this->filter['hx']==6){
				$sqlWhere .=  " and i.fx_s >5";
			}else{
				$sqlWhere .=  " and i.fx_s = '{$this->filter['hx']}'";
			}
		}else{
			$sqlWhere .= '';
		}
		//处理面积
		if(isset($this->filter['mj'])){
			switch ($this->filter['mj']){
				case 1:
				 $sqlWhere .= " and i.jzmj<=100";
				  break;
				case 2:
				  $sqlWhere .= " and i.jzmj >=100 and i.jzmj <= 200";
				  break;
				case 3:
				  $sqlWhere .= " and i.jzmj >=200 and i.jzmj <= 300";
				  break;
				 case 4:
				  $sqlWhere .= " and i.jzmj >=300 and i.jzmj <= 500";
				  break;
				 case 5:
				  $sqlWhere .= " and i.jzmj >=500 and i.jzmj <= 800";
				  break;
				 case 6:
				  $sqlWhere .= " and i.jzmj >=800 and i.jzmj <= 1000";
				  break;
				 case 7:
				  $sqlWhere .= " and i.jzmj >=1000 and i.jzmj <= 2000";
				  break;
				case 8:
				  $sqlWhere .= " and i.jzmj >=2000";
				  break;
				default:
				  echo "";
			}
		}
		//处理面积输入区间查询
		if(isset($this->filter['mjh'])){
			$mjl = isset($this->filter['mjl'])?$this->filter['mjl']:'0';
			$sqlWhere .= " and i.jzmj >={$mjl} and i.jzmj <=  '{$this->filter['mjh']}'";
		}
		//处理房龄
		if(isset($this->filter['fl'])){
			$time = date(Y,time());
			$time_2 = $time-2;
			$time_5 = $time-5;
			$time_10 = $time-10;
			switch ($this->filter['fl']){
				case 1:
				 $sqlWhere .= " and i.jznd>={$time_2}";
				  break;
				case 2:
				  $sqlWhere .= " and i.jznd >={$time_5} and i.jznd<= {$time_2}";
				  break;
				case 3:
				 $sqlWhere .= " and i.jznd >={$time_10} and i.jznd<= {$time_5}";
				  break;
				case 4:
				 $sqlWhere .= " and i.jznd <=({$time_10})";
				  break;
				 
				default:
				  echo "";
			}
		}
	
		//处理楼层
		if(isset($this->filter['lc'])){
			switch ($this->filter['lc']){
				case 1:
				 $sqlWhere .= " and i.lc_l<1";
				  break;
				case 2:
				  $sqlWhere .= " and i.lc_l=1";
				  break;
				case 3:
				  $sqlWhere .= " and i.lc_l >=1 and i.lc_l <= 6";
				  break;
				 case 4:
				  $sqlWhere .= " and i.lc_l >=6 and i.lc_l <= 12";
				  break;
				case 5:
				  $sqlWhere .= " and i.lc_l >=12";
				  break;
				default:
				  echo "";
			}
		}
        $sqlWhere .= isset($this->filter['kw_title'])  ? " and i.title like('%{$this->filter['kw_title']}%')" : '';
  /*       $sqlWhere .= isset($this->filter['kw_bTime'])  ? " and i.put_time > ".ext('unixtime',$this->filter['kw_bTime'],'0:0:0') : '';
        $sqlWhere .= isset($this->filter['kw_eTime'])  ? " and i.put_time <= ".ext('unixtime',$this->filter['kw_eTime'],'23:59:59') : '';
        $cateId = isset($this->filter['kw_cate']) ? $this->filter['kw_cate'] : $this->conf->get('articleCateId');
       
        $sqlWhere .= " and i.cate_id in (".implode(Category::getAllChild($cateId),',').")"; */
      
        return $sqlWhere;
    }

    
    function getTotal($sqlWhere){
        return $this->db->GetOne("select count(*) as total from {$this->tab} i left join {$this->tab_user} u on i.m_id=u.id {$sqlWhere}");
    }

    
    function getJsonData(){
        return array(
            
            /* //置顶
            'kw_order' => array(
                'value' => $this->filter['kw_order'],
                'list' => array(
                    '1' => $this->lang->get('global_orderEnabled'),
                    '2' => $this->lang->get('global_orderDisabled'),
                   
                ),
            ), */
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
			
			'item[qy]' => Category::getListP($this->conf->get('quyuCateId')),//区域
            'item[xx_xx]' => Category::getSelList( $item['xx_xx'], $this->conf->get('xiaoxueCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),//学校_小学
            'item[xx_cz]' => Category::getSelList( $item['xx_cz'], $this->conf->get('cuzhongCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),//学校_初中
            //'item[dt]' => Category::getSelList( $item['dt'], $this->conf->get('ditieCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),//地铁
			'item[dt]' => Category::getListP($this->conf->get('ditieCateId')),//地铁
            'item[cx]' => Category::getSelList( $item['cx'], $this->conf->get('chaoxiangCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),//朝向
            'item[zxcd]' => Category::getSelList( $item['zxcd'], $this->conf->get('zxcdCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),//装修程度
            'item[ts]' => Category::getSelList( $item['ts'], $this->conf->get('teseCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),//特色
            'item[sq]' => Category::getSelList( $item['sq'], $this->conf->get('shangquanCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),//商圈
            'item[fwpt]' => Category::getSelList( $item['fwpt'], $this->conf->get('fwptCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),//房屋配套
            'item[dg]' => Category::getSelList( $item['dg'], $this->conf->get('dianguiCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),//电轨
            'item[yxq]' => Category::getSelList( $item['yxq'], $this->conf->get('youxiaoqiCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),//有效期
            'item[jzlx]' => Category::getSelList( $item['jzlx'], $this->conf->get('jianzhuleixinCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),//建筑类型
            'item[jg]' => Category::getSelList( $item['jg'], $this->conf->get('fangwujiegouCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),//房屋结构
            'item[zzlb]' => Category::getSelList( $item['zzlb'], $this->conf->get('zhuzhaileibieCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),//筑宅类别
            'item[cqxz]' => Category::getSelList( $item['cqxz'], $this->conf->get('chanquanxinzhiCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[kfsj]' => Category::getSelList( $item['kfsj'], $this->conf->get('kanfangshijianCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),//开放时间
			
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
