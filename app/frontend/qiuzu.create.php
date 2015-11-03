<?php
/*-----------------------------------------------------+
 * 发布出租
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
include_once(LIB_DIR.'/category.class.php');
class Create extends Page{
    //var $AuthLevel = ACT_OPEN;
    var $AuthLevel = ACT_NEED_LOGIN;
    var $db;
    var $tab = 'fang';

    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
    }

    function process(){
        //检查是否提交数据
        if(!empty($this->input['submit'])){
            $this->submit();
            return;
        }
		
        //输出页面
        $this->export();
    }

    function submit(){
        //获取提交的数据
        $item = stripQuotes(trimArr($this->input['item']));
		
        //检查数据合法性
        $emsg = $this->validate($item);
        //如果数据不合法则输出
        if(count($emsg)){
            $this->export($item, $emsg);
            return;
        }

        //数据合法则写入数据
        if(!$this->insertDb($item)){
            //写入失败则输出信息
            Core::raiseMsg($this->lang->get('e_article_invalidationManipulation'));
        }
        Core::redirect(Core::getUrl('showlist','','',true));
    }

    function export($item=null, $emsg=null){
		$result = $this->getJsonData($item, $emsg);
		$list_xx =array();
		$list_cz =array();
		foreach($result[ 'item[qy]' ] as $value){
			$sq_pid = Category::getPidId($value['id'],'商圈');
			$xx_pid = Category::getPidId($value['id'],'小学');
			$cz_pid = Category::getPidId($value['id'],'初中');
			if($sq_pid){
				$sq_l = Category::getListP($sq_pid);
				foreach($sq_l as $sq_v){
					$list[] = array($value['id'],$sq_v['title_zh'],$sq_v['id']);
				}
			}
		} 
		foreach($result[ 'item[dt]' ] as $value){
				$sq_l = Category::getListP($value['id']);
				foreach($sq_l as $sq_v){
					$list_dtxl[] = array($value['id'],$sq_v['title_zh'],$sq_v['id']);
				}
		} 
        //页面输出的数据
        $pvar = array(
            'item' => $item,
			'list' => $list,
			'list_dtxl' => $list_dtxl,
			'zffs_zzbs' => $this->lang->get('zffs_zzbs'),
			'ptss_zz' => $this->lang->get('ptss_zz'),
			'ptss_bs' => $this->lang->get('ptss_bs'),
			'fwpt_sp' => $this->lang->get('fwpt_sp'),
			'kjylb_sp' => $this->lang->get('kjylb_sp'),
			'lb_sp' => $this->lang->get('lb_sp'),
			'splx_sp' => $this->lang->get('splx_sp'),
			'dqzt_sp' => $this->lang->get('dqzt_sp'),
			'zffs_sp' => $this->lang->get('zffs_sp'),
			'lx_xzl' => $this->lang->get('lx_xzl'),
			'jb_xzl' => $this->lang->get('jb_xzl'),
			'iswyf_xzl' => $this->lang->get('iswyf_xzl'),
			'hz_fx_ws' => $this->lang->get('hz_fx_ws'),
			'hz_fx_fjs' => $this->lang->get('hz_fx_fjs'),
			'hz_fx_xbxz' => $this->lang->get('hz_fx_xbxz'),
			'zj_dw_xzl' => $this->lang->get('zj_dw_xzl'),
			'fx_qz' => $this->lang->get('fx_qz'),
			'isfg' => $this->lang->get('isfg'),
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_article_createTitle'),
            'formAct' => Core::getUrl('','','','','true'),
        );
		//echo "<pre>";
		//print_r($pvar[ 'lx_xzl']); //显示区域信息
		//print_r($pvar[ 'jsonData']['item[fytype]']['list']); //显示区域信息
        $this->assign('v', stripQuotes($pvar));
        $this->addTplFile('form');
        $this->display();
    }

    function getJsonData($item, $emsg=null){
        return array(
            'emsg' => $emsg,
            
           /*  'item[state]' => array(
                'display' => true,
                'value' => 0,
                'list' => array(
                    '0' => $this->lang->get('global_stateDisabled'),
                    '1' => $this->lang->get('global_stateEnabled'),
                    //'2' => $this->lang->get('global_stateDelete'),
                ),
            ), */

            //类别选择器
			//区域
            'item[qy]' => Category::getListP($this->conf->get('quyuCateId')),//区域
            'item[xx_xx]' => Category::getSelList( $item['xx_xx'], $this->conf->get('xiaoxueCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[xx_cz]' => Category::getSelList( $item['xx_cz'], $this->conf->get('cuzhongCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
             'item[dt]' => Category::getListP(  $this->conf->get('ditieCateId')),
            'item[cx]' => Category::getSelList( $item['cx'], $this->conf->get('chaoxiangCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[zxcd]' => Category::getSelList( $item['zxcd'], $this->conf->get('zxcdCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[ts]' => Category::getSelList( $item['ts'], $this->conf->get('teseCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[sq]' => Category::getSelList( $item['sq'], $this->conf->get('shangquanCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[fwpt]' => Category::getSelList( $item['fwpt'], $this->conf->get('fwptCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[dg]' => Category::getSelList( $item['dg'], $this->conf->get('dianguiCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[fytype]' => Category::getSelList( $item['fytype'], $this->conf->get('fangyuanleixinCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[czfs]' => Category::getSelList( $item['czfs'], $this->conf->get('chuzufangshiCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[yxq]' => Category::getSelList( $item['yxq'], $this->conf->get('youxiaoqiCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),

            'goBackLink' =>array('url' => Core::getUrl('showlist','','',true)),
        );
    }

    /*
	*插入数据
	*/
    function insertDb($item){
       unset($item['id']);//防止ID号被修改
        $item['content'] = absoluteToRelative($item['content']);
		$item['m_id'] = $this->sess->get('memberid');
		if(is_array($item['ptss_zz'])){
			$item['ptss_zz'] = implode(',',$item['ptss_zz'] );
		}
		if(is_array($item['fx_qz'])){
			$item['fx_qz'] = implode(',',$item['fx_qz'] );
		}
		$item['state'] =1;
		$item['ftype'] =4;
		$item['bh'] =createBianhao(); //创建编号
        if($item['is_order']){
        	$item['is_order']=$this->countOrder()+1;
        }
        $item['put_time'] = time();
        $item['edit_time'] = time();
		$item['sq'] = $this->input['s2'];
		$item['dtxl'] = $this->input['dtxl'];
        $sql = "select * from {$this->tab} where id=-1";
        $sql = $this->db->GetInsertSQL($this->db->Execute($sql), $item);
        return $this->db->Execute($sql);
    }

    function isExist($title){
        $sql = "select count(*) from {$this->tab} where title='{$title}'";
        return $this->db->GetOne($sql) ? true : false;
    }

    
    function countOrder(){
        $sql = "select is_order from {$this->tab} where is_order<>0 order by  is_order desc";
        return $this->db->GetOne($sql);
    }
    
    
    function validate($i){
        $e = array();
        if(!$i['title']){
            PhpBox('标题不能为空！！！!');
    		GotoPage(h);
    		exit;
        }elseif($this->isExist($i['title'])){
            PhpBox('标题已经存在!');
    		GotoPage(h);
    		exit;
        }

        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
