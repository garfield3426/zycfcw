<?php
/*-----------------------------------------------------+
 * 发布医生信息
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
//include_once(LIB_DIR.'/category.class.php');
class Create extends Page{
    var $db;
    var $tab = 'doctor';
    var $tab_branch = 'branch';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_branch = $this->conf->get('table_prefix').$this->tab_branch;
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
            Core::raiseMsg($this->lang->get('e_doctor_invalidationManipulation'));
        }
        Core::redirect(Core::getUrl('showlist'));
    }

    
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'hospital' =>  getAllHospital(),
            'branch' =>  $this->getBranch(),
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_doctor_createTitle'),
            'formAct' => Core::getUrl(),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->addTplFile('form');
        $this->display();
    }

    
    function getJsonData($item, $emsg=null){
       
        return array(
            'emsg' => $emsg,
            
            'item[state]' => array(
                'display' => true,
                'value' => 1,
                'list' => array(
                    '0' => $this->lang->get('global_stateDisabled'),
                    '1' => $this->lang->get('global_stateEnabled'),
                ),
            ),

            'goBackLink' =>array('url' => Core::getUrl('showlist','','',true)),
        );
    }

    
    function insertDb($item){
        unset($item['id']);//防止ID号被修改
        $item['editor'] = $this->sess->getUserId();
        $item['intro'] = absoluteToRelative($item['intro']);
        $sql = "select * from {$this->tab} where id=-1";
        $sql = $this->db->GetInsertSQL($this->db->Execute($sql), $item);
        return $this->db->Execute($sql);
    }

    //获取科室数据
    function getBranch(){
        $sql = "select * from {$this->tab_branch} where state=1 and h_id=".CONTENT_SKY;
        $row = $this->db->GetAll($sql);
        return $row;
    }
        
    function validate($i){
        $e = array();
        if(!is_numeric($i['state'])){
            $e['state'] = $this->lang->get('e_doctor_stateIsEmpty');
        }
        if(!$i['name']){
            $e['name'] = $this->lang->get('e_doctor_nameIsEmpty');
        }
        if(!strlen($i['h_id'])){
            $e['h_id'] = $this->lang->get('e_doctor_hospitalIsEmpty');
        }
        if(!strlen($i['b_id'])){
            $e['b_id'] = $this->lang->get('e_doctor_branchIsEmpty');
        }
        if(!strlen($i['keyword'])){
            $e['keyword'] = $this->lang->get('e_doctor_keywordIsEmpty');
        }
        if(!strlen($i['duty'])){
            $e['duty'] = $this->lang->get('e_doctor_dutyIsEmpty');
        }        
        if(!strlen($i['rank'])){
            $e['rank'] = $this->lang->get('e_doctor_rankIsEmpty');
        }
        if(!strlen($i['info'])){
            $e['info'] = $this->lang->get('e_doctor_infoIsEmpty');
        }
        /*if(!strlen($i['intro'])){
            $e['intro'] = $this->lang->get('e_doctor_introIsEmpty');
        }*/
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
