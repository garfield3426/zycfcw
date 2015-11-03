<?php
/*-----------------------------------------------------+
 * 回复留言
 *
 * @author Try.Shieh@gamil.com 
 +-----------------------------------------------------*/
class Reply extends Page{
    var $db;
    var $currentId;
    var $tab = 'client';

    
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
            Core::raiseMsg($this->lang->get('e_client_idIsEmpty'));
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
        if($item['email']){
            if($this->sendEmail($item)){
                Core::raiseMsg($this->lang->get('e_client_replyToEmailLost'));
            }
        }
        //如果需要回复到邮箱
        /*if($item['replyToEmail']){
            if(!$this->sendMail($item)){
                Core::raiseMsg($this->lang->get('e_bespeak_replyToEmailLost'));
            }
        }*/
        
        //更新数据库
        if(!$this->updateDb($item)){
            Core::raiseMsg($this->lang->get('e_bespeak_replyLost'));
        }
        //提示成功回复
        Core::raiseMsg(
            $this->lang->get('e_client_replySucceed'),
            array(
                $this->lang->get('p_client_goBackToList') => Core::getUrl('showlist', '', '', true)
            )
        );
    }

    
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'item' => $item,
            'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_client_replyTitle'),
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

    
    /*function sendMail($item){
        include_once(LIB_DIR.'/mailsender.class.php');
        $item['now'] = date('Y-m-d H:i:s', time());
        $send =& new MailSender();
        $body =<<<AAA
			
尊敬的{$item['name']}：
您好！
根据您提出的关于"{$item['title']}"的留言
        
普瑞眼科医院已在{$item['now']}回复，回复内容为：{$item['reply']}

感谢你对普瑞眼科医院的信任。 

温馨提示： 
 
1、普瑞医院统一服务邮箱为 service@purui.com ，请注意邮件发送者，谨防假冒！
2、本邮件为系统自动发出，请勿回复。 
 
感谢您使用普瑞眼科医院网！

AAA;
     
        return $send->send($item['email'], $item['name'], $item['title'], $body, $this->conf->get('site_name'));
    }*/
    
    function sendEmail($item){
		$item['now'] = date('Y-m-d H:i:s', time());
        require_once( LIB_DIR.'/sendmail.php' );
        $sm = new smail( "qq66621979@126.com", "fallingstarhorse", "smtp.126.com" ); //实例化类smail
		$subject  = "=?UTF-8?B?".base64_encode('qq66621979@126.com')."?=";			 //将发件人实行uft-8编码
		$headers  = "=?UTF-8?B?".base64_encode('上海普瑞眼科医院留言回复')."?=" . "\r\n";//如果没有这段则出现乱码
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$body =<<<AAA
			
<p>尊敬的{$item['name']}：</p>
<p>您好！</p>
<p>　</p>
<p>根据您提出的关于"{$item['title']}"留言</p>
<p>　</p>
<p>普瑞眼科医院已在{$item['now']}回复，回复内容为:</p>
<p><span class="STYLE1">{$item['reply']}</span></p>
<p>感谢你对普瑞眼科医院的信任。</p>
<p>温馨提示：<br>
<br>
1、普瑞医院统一服务邮箱为 admin@purui.com ，请注意邮件发送者，谨防假冒！</p>
<p>2、本邮件为系统自动发出，请勿回复。</p>
<p>　</p>
<p>感谢您使用普瑞眼科医院网！</p>

AAA;
		return  $sm->send( $item['email'], $subject, $headers,$body );
    }
    
    
    
        /*function sendEmail($item){
			$item['now'] = date('Y-m-d H:i:s', time());
	        include_once( LIB_DIR.'/mailsender.class.php');
	         $send =& new MailSender();
	        //$sm = new smail( "qq66621979@126.com", "fallingstarhorse", "smtp.126.com" ); //实例化类smail
			$subject  = "=?UTF-8?B?".base64_encode('qq66621979@126.com')."?=";			 //将发件人实行uft-8编码
			$headers  = "=?UTF-8?B?".base64_encode('上海普瑞眼科医院留言回复')."?=" . "\r\n";//如果没有这段则出现乱码
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$body =<<<AAA
			
<p>尊敬的{$item['name']}：</p>
<p>您好！</p>
<p>　</p>
<p>根据您提出的关于"{$item['title']}"留言</p>
<p>　</p>
<p>普瑞眼科医院已在{$item['now']}回复，回复内容为<span class="STYLE1">{$item['reply']}</span>。感谢你对普瑞眼科医院的信任。</p>
<p>温馨提示：<br>
<br>
1、普瑞医院统一服务邮箱为 service@purui.com ，请注意邮件发送者，谨防假冒！</p>
<p>3、本邮件为系统自动发出，请勿回复。</p>
<p>　</p>
<p>感谢您使用普瑞眼科医院网！</p>

AAA;
 return $send->send($item['email'], $item['name'], $item['title'], $body, $this->conf->get('site_name'));
			//return$end = $sm->send( $item['reply'], $subject, $headers,$body );
    }*/

    
    function validate($i){
        $e = array();
        if(!strlen($i['reply'])){
            $e['reply'] = $this->lang->get('e_client_replyIsEmpty');
        }
        //将错误信息的key转换为表单的name
        $emsg = array();
        foreach($e as $key=>$val) $emsg["item[{$key}]"] = $val;
        return $emsg;
    }
}
?>
