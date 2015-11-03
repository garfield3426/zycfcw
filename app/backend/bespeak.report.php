<?php

//require_once LIB_DIR.'/category.class.php';
require LIB_DIR.'/php-excel.class.php';
class Report extends Page{

    var $AuthLevel = ACT_OPEN;
    var $db;
    var $tab = 'bespeak';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
    }

    
    function process(){
	    $this->report();
    }
    
    
    function getData(){
        $sql = "select id,name,sex,age,operation_time,types,cdkey,tel,qq,email,address,content,put_time,reply,reply_time from {$this->tab} where state<>2 order by id desc";
        $item = $this->db->GetAll($sql);
        return $item;
    }
    
    
	public function report(){	
		$list = $this->getData();
		
		$type = unserialize( BESPEAK_TYPE );
		foreach($list as $key=>$value){
			if($value['sex']==1){
				$value['sex']='男';
			}elseif($value['sex']==0){
				$value['sex']='女';
			}else{
				$value['sex']='未填写';
			}
			
			$value['types']=$type[$value['types']];
			$value['operation_time']=date('Y-m-d',$value['operation_time']);
			$value['put_time']=date('Y-m-d',$value['put_time']);
			$value['reply_time']=date('Y-m-d',$value['reply_time']);
			$value['content']=strip_tags($value['content']);//strip_tags去除html或php代码
			$value['reply']=strip_tags($value['reply']);//strip_tags去除html或php代码
			
			$lists[]=$value;
		}

		//id,name,sex,age,operation_time,types,cdkey,tel,email,address,content,put_time,reply_time,reply
		$key=array('id','留言人姓名','性别','年龄','手术日期','手术类型','预约号','电话','qq','email','地址','病情说明','留言时间','回复内容','回复时间');
		array_unshift($lists,$key);//在数组开头插入一个数组
		$xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
		$xls->addArray($lists);
		$name='Report_'.date(Ymd);
		$xls->generateXML($name);
	}

}
?>
