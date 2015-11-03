<?php
/*-----------------------------------------------------+
 * 注册会员
 *
 * @author Try.Shieh@gamil.com 
 +-----------------------------------------------------*/
class edit extends Page{
    var $AuthLevel = ACT_OPEN;
    var $db;
    var $groupId = 1;
    var $tab = 'member';

    
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
        //$emsg = $this->validate($item);
        //如果数据不合法则输出
       
        //数据合法则更新数据
        $this->updateDb($item);
        //返回列表页
        Core::redirect(Core::getUrl('showlist','product','',true));
    }

    
    function export($item=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
           
            //'title' => $this->lang->get('p_article_editTitle'),
            'formAct' => Core::getUrl(),
        );
        $this->assign('v', stripQuotes($pvar));
      
        $this->display();
    }

  
    

    
    function getData(){
        $sql = "select * from {$this->tab} where id={$this->currentId}";
        $item = $this->db->GetRow($sql);
 
        //$item['put_time'] = date('Y-m-d', $item['put_time']);
        //$item['content'] = relativeToAbsolute($item['content']);
        return $item;
    }

    
    function updateDb($item){
        unset($item['id']);//防止ID号被修改
        $item['is_subscription'] = $item['is_subscription']?'1':'0';
        $sql = "select * from {$this->tab} where id={$this->currentId}";
        $sql = $this->db->GetUpdateSQL($this->db->Execute($sql), $item);
        return $this->db->Execute($sql);
    }  
}
?>