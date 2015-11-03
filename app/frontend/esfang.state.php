<?php
/*-----------------------------------------------------+
 * 改变房产状态
 *
 * value = 0 未发布
 * value = 1 已发布
 * value = 2 已删除(非实际删除)
 *条件：id存在,且每个会员只能删除他自己发布的
 * @author maorenqi 
 +-----------------------------------------------------*/
class State extends Action{
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
        $id = $this->input['id'];
        $val = (int)$this->input['value'];
		$m_id = $this->sess->get('memberid');
		
        if(0 > $val || 2 < $val){
            PhpBox('参数错误!');
    		GotoPage(h);
    		exit;
        }
        if(is_array($id)){
            $idStr = implode(',',$id);
        }elseif(!is_numeric($id)){
            PhpBox('参数错误!');
    		GotoPage(h);
    		exit;
        }else{
            $idStr = $id;
        }
		$sql = "select  count(*)  from {$this->tab} where id={$id} and m_id={$m_id}";
		$result = $this->db->GetOne($sql);
		$sql = "select  count(*)  from {$this->tab} where id={$id} and m_id={$m_id}";
		$result = $this->db->GetRow($sql);
		if(!$result){
			PhpBox('你的权限不够!');
    		GotoPage(h);
    		exit;
		} 
        if(2 == $val) Core::log(L_NOTICE, sprintf($this->lang->get('log_article_delete'), $idStr));
        $sql = "update {$this->tab} set state={$val} where id in({$idStr})";
        $this->db->Execute($sql);
		$sql = "select  ftype,fytype from {$this->tab} where id={$id} and m_id={$m_id}";
		$result_s = $this->db->GetRow($sql);
		if($result_s['ftype']=='1'){
			Core::redirect(Core::getUrl('lists','fang',array('ftype'=>$result_s['ftype'],'fytype'=>$result_s['fytype']),'',true));
		}else if($result_s['ftype']=='2'){
			Core::redirect(Core::getUrl('lists','rent','','',true));
		}else if($result_s['ftype']=='3'){
			Core::redirect(Core::getUrl('lists','qiugou','','',true));
		}else if($result_s['ftype']=='4'){
			Core::redirect(Core::getUrl('lists','qiuzu','','',true));
		}
    }
}
?>
