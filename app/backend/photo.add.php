<?php
/*-----------------------------------------------------+
 * 添加图片文章图片
 *
 * @author maorenqi 
 +-----------------------------------------------------*/

class Add extends Page{
    var $db;
    var $tab = 'album';

    /**
     * 构造方法
     */
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
      
        $this->db = getDb();
        //$this->db->debug = true;
    }

    /**
     * 程序入口
     */
    function process(){
    
        //检查是否提交数据
        if(!empty($this->input['submit'])){
            $this->submit();
            return;
        }
        //输出页面
        $this->export();
    }

    /**
     * 提交数据
     */
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
     
        $counts = count($this->input['imageTitle']);
        //数据合法则写入数据
        if(!$this->insertDb($item,$counts)){
            //写入失败则输出信息
            Core::raiseMsg($this->lang->get('e_album_invalidationManipulation'));
        }
        $this->updatePhotos($this->input['imageTitle'],$this->db->Insert_ID(),$item);
        Core::redirect(Core::getUrl('showlist'));
        
    }

    /**
     * 显示页面
     */
    function export($item=null, $emsg=null){
    	if($this->input['album_id']){
    		$album_id = $this->input['album_id'];
    	}
    	      
        //页面输出的数据
        $pvar = array(
        	'album_id'=>$album_id,
            'album'=>$this->getAlbum(),
            'title' => $this->lang->get('p_album_createTitle'),
            'formAct' => Core::getUrl(),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->addTplFile('form');
        $this->display();
    }
 
    /**
     * 获得需要JSON输出的数据
     * @param array $item
     * @return array
     */
    function getJsonData($item, $emsg=null){
        return array(
            'emsg' => $emsg,
            'item[state]' => array(
                'display' => true,
                'value' => $item['state'],
                'list' => array(
                    '0' => $this->lang->get('global_stateDisabled'),
                    '1' => $this->lang->get('global_stateEnabled'),
                    //'2' => $this->lang->get('global_stateDelete'),
                ),
            ),
            'goBackLink' =>array('url' => Core::getUrl('showlist','','',true)),
        );
    }

     function getAlbum(){
        $sql = "select * from {$this->tab} where state=1";
        $row = $this->db->GetAll($sql);
        return $row;
    }
    
   
    function validate($i){
        $e = array();
        if(!$i['title']){
            $e['title'] = $this->lang->get('e_album_titleIsEmpty');
        }
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
