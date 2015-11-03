<?php
/*-----------------------------------------------------+
 * 修改发布了的出售信息
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
include_once(LIB_DIR.'/category.class.php');
class Edit extends Page{
	var $AuthLevel = ACT_NEED_LOGIN;
    var $db;
    var $currentId;
    var $tab = 'fang';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
    }

    
    function process(){
        //获取当前的操作id
        $this->currentId = is_numeric($this->input['id'])
            ? $this->input['id']
            : $this->input['item']['id'];
        //没有则返回错误信息
        if(!is_numeric($this->currentId)){
            Core::raiseMsg($this->lang->get('e_article_idIsEmpty'));
        }
        //检查是否提交数据
        if(!empty($this->input['submit'])){
            $this->submit();
            return;
        }

        //输出页面
        $this->export(stripQuotes($this->getData()));
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
        //数据合法则更新数据
        $this->updateDb($item);
        //返回列表页
        Core::redirect(Core::getUrl('lists','',array('ftype'=>1,'fytype'=>$item['fytype']),'',true));
    }

    
    function export($item=null, $emsg=null){
		$result = $this->getJsonData($item, $emsg);
		$list =array();
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

		$sq_pid = Category::getPidId($item[qy],'商圈');
		if($sq_pid){
			$value_sq = Category::getListP($sq_pid);
		}
		
        //页面输出的数据
        $pvar = array(
            'item' => $item,
			'list' => $list,
            'value_sq' => $value_sq,
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
			'isfg' => $this->lang->get('isfg'),
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_article_editTitle'),
            'formAct' => Core::getUrl('','','','','true'),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->addTplFile('form');
        $this->display();
    }

    
    function getJsonData($item, $emsg=null){
        //如果只支持一种语言,则给lang加上默认值.
        if(count($this->conf->get('language_support')) < 2){
            $item['lang'] = strlen($item['lang']) ? $item['lang'] : $this->conf->get('language_default');
        }
        return array(
            'emsg' => $emsg,
            
            'item[state]' => array(
                'value' => $item['state'],
                'list' => array(
                    '0' => $this->lang->get('global_stateDisabled'),
                    '1' => $this->lang->get('global_stateEnabled'),
                    //'2' => $this->lang->get('global_stateDelete'),
                ),
            ),
           //类别选择器
             'item[qy]' => Category::getListP($this->conf->get('quyuCateId')),//区域
            'item[xx_xx]' => Category::getSelList( $item['xx_xx'], $this->conf->get('xiaoxueCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[xx_cz]' => Category::getSelList( $item['xx_cz'], $this->conf->get('cuzhongCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[dt]' => Category::getSelList( $item['dt'], $this->conf->get('ditieCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[cx]' => Category::getSelList( $item['cx'], $this->conf->get('chaoxiangCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[zxcd]' => Category::getSelList( $item['zxcd'], $this->conf->get('zxcdCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[ts]' => Category::getSelList( $item['ts'], $this->conf->get('teseCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[sq]' => Category::getSelList( $item['sq'], $this->conf->get('shangquanCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[fwpt]' => Category::getSelList( $item['fwpt'], $this->conf->get('fwptCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[dg]' => Category::getSelList( $item['dg'], $this->conf->get('dianguiCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
             'item[yxq]' => Category::getSelList( $item['yxq'], $this->conf->get('youxiaoqiCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
			'item[jzlx]' => Category::getSelList( $item['jzlx'], $this->conf->get('jianzhuleixinCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[jg]' => Category::getSelList( $item['jg'], $this->conf->get('fangwujiegouCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[zzlb]' => Category::getSelList( $item['zzlb'], $this->conf->get('zhuzhaileibieCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[cqxz]' => Category::getSelList( $item['cqxz'], $this->conf->get('chanquanxinzhiCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[kfsj]' => Category::getSelList( $item['kfsj'], $this->conf->get('kanfangshijianCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
			 'item[fytype]' => Category::getSelList( $item['fytype'], $this->conf->get('fangyuanleixinCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[czfs]' => Category::getSelList( $item['czfs'], $this->conf->get('chuzufangshiCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
           
            'goBackLink' =>array('url' => Core::getUrl('showlist','','',true)),
        );
    }

    
    function getData(){
        $sql = "select * from {$this->tab} where id={$this->currentId}";
        $item = $this->db->GetRow($sql);
       // $item['put_time'] = date('Y-m-d H:i:s', $item['put_time']);
        $item['content'] = relativeToAbsolute($item['content']);
        $item['fwpt']= explode(',',$item['fwpt'] );
        $item['ptss_zz']= explode(',',$item['ptss_zz'] );
        $item['ptss_bs']= explode(',',$item['ptss_bs'] );
        $item['fwpt_sp']= explode(',',$item['fwpt_sp'] );
        $item['kjylb_sp']= explode(',',$item['kjylb_sp'] );
        $item['sn_images']= explode(',',$item['sn_images'] );
       // $item['xq_images']= explode(',',$item['xq_images'] );
        return $item;
    }

    
    function updateDb($item){
    	
		/* $items[editor1]='普瑞眼科编辑部';
    	$sql1 = "select * from {$this->tab} where state=1";
        $sql1 = $this->db->GetUpdateSQL($this->db->Execute($sql1), $items);
        $this->db->Execute($sql1); */
        unset($item['id']);//防止ID号被修改
        $item['content'] = absoluteToRelative($item['content']);
        $item['edit_time'] = time();
        //$item['put_time'] = strlen($item['put_time'])?strtotime($item['put_time']):time();
        $item['is_order'] = $item['is_order']?$this->countOrder()+1:0;
		if(is_array($item['fwpt'])){
			$item['fwpt'] = implode(',',$item['fwpt'] );
		}
		if(is_array($item['ptss_zz'])){
			$item['ptss_zz'] = implode(',',$item['ptss_zz'] );
		}
		
		if(is_array($item['ptss_bs'])){
			$item['ptss_bs'] = implode(',',$item['ptss_bs'] );
		}
		
		if(is_array($item['fwpt_sp'])){
			$item['fwpt_sp'] = implode(',',$item['fwpt_sp'] );
		}
		
		if(is_array($item['kjylb_sp'])){
			$item['kjylb_sp'] = implode(',',$item['kjylb_sp'] );
		}
		$item['sq'] = $this->input['s2'];
		if(is_array($item['sn_images'])){
			$item['sn_images'] = implode(',',$item['sn_images'] );
		}
        $sql = "select * from {$this->tab} where id={$this->currentId}";
        $sql = $this->db->GetUpdateSQL($this->db->Execute($sql), $item);
        return $this->db->Execute($sql);
    }

    
    function isExist($title){
        $sql = "select count(*) from {$this->tab} where title='{$title}' and id<>{$this->currentId}";
        return $this->db->GetOne($sql) ? true : false;
    }
    
    
    function countOrder(){
        $sql = "select is_order from {$this->tab} where is_order<>0 order by is_order desc";
        return $this->db->GetOne($sql)?$this->db->GetOne($sql):0;
    }

    
    function validate($i){
        if(!$i['title']){
            PhpBox('标题不能为空！！！!');
    		GotoPage(h);
    		exit;
        }
    }
}
?>
