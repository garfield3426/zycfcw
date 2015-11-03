<?php

//require_once LIB_DIR.'/category.class.php';
class Save extends Page{

    var $AuthLevel = ACT_OPEN;
    var $db;
    var $tab = 'doctor_zuozhen';

    
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
    }

    
    function process(){
    	if($this->input['id']){
	        $item = trimArr($this->input['item']);
	       
	        if(!$this->insertDb($item)){
	            Core::raiseMsg($this->lang->get('z_zuozhen_postAbortive'));
	        }else{
	            Core::raiseMsg(
	                $this->lang->get('z_zuozhen_postSuccessful'),
	                array($this->lang->get('p_zuozhen_goBackToList') => Core::getUrl('showlist'))
	            );
	        }
    	}
    }

    
    function insertDb($i){
    	$itemdate=trimArr($this->input['itemdate']);  	
    	$itemdate['h_id']=CONTENT_SKY;
		$this->updateDb($itemdate,$itemdate['doctor_id'],true);//把数据库里所有为当前医生ID的数据全部删除
    	//处理数据
    	foreach($i as $k=>$v){
    		if( strstr( $k,'AM') ){
    			$itemdate['amorpm'] = 1;//上午
    		}
    		elseif( strstr( $k, 'PM') ){
    			$itemdate['amorpm'] = 2;//下午
    		}
    		$itemdate['zuozhen_date']=$v;
    		
    		//如果数据库里有state＝2的数据则更新，否则插入数据
    		if($this->getdb()){
    			$result=$this->updateDb($itemdate,$this->getdb());
    		}else{
    			$result=$this->insertdate($itemdate);
    		}
    	}
        return $result;
    }
	function getdb(){
		$sql = "select id from {$this->tab} where state=2";
        $rs = $this->db->GetOne($sql);
        return $rs;
	}
   
	
    function updateDb($item,$currentid,$state=null){
        unset($item['id']);//防止ID号被修改
        if($state){
        	$item['state'] = 2;
        	$where=" where doctor_id=$currentid";
        }else{
        	$item['state'] = 1;
        	$where=" where id=$currentid";
        }
        	
        $sql = "select * from {$this->tab}$where";
        $sql = $this->db->GetUpdateSQL($this->db->Execute($sql), $item);

        return $this->db->Execute($sql);
    }
    
    
    function insertdate($item){
        unset($item['id']);//防止ID号被修改
        $item['state'] = 1;
        $sql = "select * from {$this->tab} where id=-1";
        $sql = $this->db->GetInsertSQL($this->db->Execute($sql), $item);
        return $this->db->Execute($sql);
    }

}
?>
