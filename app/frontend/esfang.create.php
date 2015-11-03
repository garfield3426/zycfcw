<?php
/*-----------------------------------------------------+
 * 发布二手房
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
        Core::redirect(Core::getUrl('lists','fang',array('ftype'=>1,'fytype'=>1),'',true));
    }

    function export($item=null, $emsg=null){
		$result = $this->getJsonData($item, $emsg);
		 /* foreach($result[ 'item[qy]' ] as &$value){
			$sq_pid = Category::getPidId($value['id'],'商圈');
			if($sq_pid){
				$value['sq'] = Category::getListP($sq_pid);
			}
			
			$xx_pid = Category::getPidId($value['id'],'小学');
			if($xx_pid){
				$value['xx'] = Category::getListP($xx_pid);
			}
			
			$cz_pid = Category::getPidId($value['id'],'初中');
			if($cz_pid){
				$value['cz'] = Category::getListP($cz_pid);
			}
			
			$result[] = $value;
		}  */
		$list =array();
		$list_xx =array();
		$list_cz =array();
		$list_dtxl =array();
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
			if($xx_pid){
				$xx_l = Category::getListP($xx_pid);
				foreach($xx_l as $xx_v){
					$list_xx[] = array($value['id'],$xx_v['title_zh'],$xx_v['id']);
				}
			}
			if($cz_pid){
				$cz_l = Category::getListP($cz_pid);
				foreach($cz_l as $cz_v){
					$list_cz[] = array($value['id'],$cz_v['title_zh'],$cz_v['id']);
				}
			}
		}  
		
		foreach($result[ 'item[dt]' ] as $value){
				$sq_l = Category::getListP($value['id']);
				foreach($sq_l as $sq_v){
					$list_dtxl[] = array($value['id'],$sq_v['title_zh'],$sq_v['id']);
				}
		}  
		
		//vardump($list_dtxl);
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'list' => $list,
            'list_xx' => $list_xx,
            'list_cz' => $list_cz,
            'list_dtxl' => $list_dtxl,
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_article_createTitle'),
            'formAct' => Core::getUrl('','','','','true'),
        );
		//vardump($this->getJsonData($item, $emsg));
        $this->assign('v', stripQuotes($pvar));
        $this->addTplFile('form');
        $this->display();
    }

    function getJsonData($item, $emsg=null){
        return array(
            'emsg' => $emsg,
            //类别选择器
			//区域
            'item[qy]' => Category::getListP($this->conf->get('quyuCateId')),//区域
            'item[xx_xx]' => Category::getSelList( $item['xx_xx'], $this->conf->get('xiaoxueCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[xx_cz]' => Category::getSelList( $item['xx_cz'], $this->conf->get('cuzhongCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
           // 'item[dt]' => Category::getSelList( $item['dt'], $this->conf->get('ditieCateId'), false, '',false,$this->lang->get('j_global_cateSelDisableMsg')),
            'item[dt]' => Category::getListP(  $this->conf->get('ditieCateId')),
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
		if(is_array($item['fwpt'])){
			$item['fwpt'] = implode(',',$item['fwpt'] );
		}
		if(is_array($item['ts'])){
			$item['ts'] = implode(',',$item['ts'] );
		}
		if(is_array($item['xq_images'])){
			$item['xq_images'] = implode(',',$item['xq_images'] );
		}
		if(is_array($item['sn_images'])){
			$item['sn_images'] = implode(',',$item['sn_images'] );
		}
		$item['state'] =0;
		$item['ftype'] =1;
		$item['fytype'] =1;
		$item['sq'] = $this->input['s2'];
		$item['xx_xx'] = $this->input['s3'];
		$item['xx_cz'] = $this->input['s4'];
		$item['dtxl'] = $this->input['dtxl'];
		
		$item['bh'] =createBianhao(); //创建编号
        if($item['is_order']){
        	$item['is_order']=$this->countOrder()+1;
        }
        $item['put_time'] = time();
        $item['edit_time'] = time();

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
            PhpBox('标题不能为空!');
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
