<?php
/*-----------------------------------------------------+
 * 回复留言
 *
 * @author Try.Shieh@gamil.com 
 +-----------------------------------------------------*/
class Reply extends Page{
    var $db;
    var $currentId;
    var $tab = 'guestbook';

    
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
            Core::raiseMsg($this->lang->get('e_guestbook_idIsEmpty'));
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
        //获取提交的数据(合并提交的数据到现有数据)
        $item = array_merge(
            stripQuotes($this->getData()),
            stripQuotes(trimArr($this->input['item']))
        );
        //检查数据合法性
        $emsg = $this->validate($item);
        //如果数据不合法则输出
        if(count($emsg)){
            $this->export($item, $emsg);
            return;
        }
        //如果需要回复到邮箱
        if($item['replyToEmail']){
            if(!$this->sendMail($item)){
                Core::raiseMsg($this->lang->get('e_guestbook_replyToEmailLost'));
            }
        }
        //更新数据库
        if(!$this->updateDb($item)){
            Core::raiseMsg($this->lang->get('e_guestbook_replyLost'));
        }
        //提示成功回复
        Core::raiseMsg(
            $this->lang->get('e_guestbook_replySucceed'),
            array(
                $this->lang->get('p_guestbook_goBackToList') => Core::getUrl('showlist', '', '', true)
            )
        );
    }

    
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_guestbook_replyTitle'),
            'formAct' => Core::getUrl(),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }

    
    function getJsonData($item, $emsg=null){
        return array(
            'emsg' => $emsg,
            'goBackLink' =>array('url' => Core::getUrl('showlist','','',true)),
        );
    }

    
    function getData(){
        $sql = "select * from {$this->tab} where id={$this->currentId}";
        $item = $this->db->GetRow($sql);
        $item['put_time'] = date('Y-m-d', $item['put_time']);
        return $item;
    }

    
    function updateDb($item){
        //防止留言内容被修改
        $data = array();
        $data['reply'] = $item['reply'];
        $data['reply_time'] = time();
        $sql = "select * from {$this->tab} where id={$this->currentId}";
        $sql = $this->db->GetUpdateSQL($this->db->Execute($sql), $data);
        return $this->db->Execute($sql);
    }

    
    function sendMail($item){
        include_once(LIB_DIR.'/mailsender.class.php');
        $send =& new MailSender();
     
        return $send->send($item['email'], $item['name'], $item['title'], $item['reply'], $this->conf->get('site_name'));
    }

    
    function validate($i){
        $e = array();
        if(!strlen($i['reply'])){
            $e['reply'] = $this->lang->get('e_guestbook_replyIsEmpty');
        }
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
