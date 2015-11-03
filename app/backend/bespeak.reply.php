<?php
/*-----------------------------------------------------+
 * 回复留言
 *
 * @author Try.Shieh@gamil.com 
 +-----------------------------------------------------*/
class Reply extends Page{
    var $db;
    var $currentId;
    var $tab = 'bespeak';

    
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
            Core::raiseMsg($this->lang->get('e_bespeak_idIsEmpty'));
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
                Core::raiseMsg($this->lang->get('e_bespeak_replyToEmailLost'));
            }
        }
        //更新数据库
        if(!$this->updateDb($item)){
            Core::raiseMsg($this->lang->get('e_bespeak_replyLost'));
        }
        //提示成功回复
        Core::raiseMsg(
            $this->lang->get('e_bespeak_replySucceed'),
            array(
                $this->lang->get('p_bespeak_goBackToList') => Core::getUrl('showlist', '', '', true)
            )
        );
    }

    
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_bespeak_replyTitle'),
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

    	/*private function sendEmail($item){
		
        require_once( LIB_DIR.'/mailsender.class.php');
        $sm = new smail( "qq66621979@126.com", "fallingstarhorse", "smtp.126.com" ); //实例化类smail
		$subject  = "=?UTF-8?B?".base64_encode('qq66621979@126.com')."?=";			 //将发件人实行uft-8编码
		$headers  = "=?UTF-8?B?".base64_encode('重置密码（请勿回复）')."?=" . "\r\n";//如果没有这段则出现乱码
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$body =<<<AAA
		
<p>尊敬的{$item['member_name']}用户：</p>
<p>您好！</p>
<p>　</p>
<p>根据您于提交的请求，本邮件将您重新设置你的帐号密码。</p>
<p>　</p>
<p>普瑞眼科医院已将你的密码重设为<span class="STYLE1">123456</span>，如果您确认本次“重新设置密码”的请求是您自己提交的，为了你的帐号安全，请及时修改密码。</p>
<p>如果您在以上时间点没有提交过“重新设置密码”的请求，则有可能是您机密问题的答案已经泄露，有恶意用户正在试图窃取您的帐号！强烈建议您赶快登录<a target="_blank" href="https://www.purui.com" style="color: blue; text-decoration: underline" target="_blank">普瑞帐号服务中心</a>修改您的密码和密码保护资料。</p>
<p>　</p>
<p>温馨提示：<br>
<br>
1、普瑞医院统一服务邮箱为 service@purui.com ，请注意邮件发送者，谨防假冒！</p>
<p>2、密码保护资料非常重要，请注意保密且一定牢记！只要您能够准确的记住您所填写的密码保护资料，即使您忘记了密码或帐号被盗，通过<a target="_blank" href="https://purui.com/admin-regist-findpwd" style="color: blue; text-decoration: underline" target="_blank">普瑞帐号服务中心</a>都可轻松找回号码。</p>
<p>3、本邮件为系统自动发出，请勿回复。</p>
<p>　</p>
<p>感谢您使用普瑞医院网！</p>

AAA;
		$end = $sm->send( $item['member_email'], $subject, $headers,$body );
    }*/
    
    function validate($i){
        $e = array();
        if(!strlen($i['reply'])){
            $e['reply'] = $this->lang->get('e_bespeak_replyIsEmpty');
        }
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
