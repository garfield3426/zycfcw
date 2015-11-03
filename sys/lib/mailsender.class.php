<?php

class MailSender {
    var $mailer;

    
    function MailSender(){
        $mailerFile = LIB_DIR.'/phpmailer/class.phpmailer.php';
        if(!file_exists($mailerFile)){
            trigger_error(_Error(0x000102, array(__FUNCTION__, 'phpMailer', $mailerFile)));
        }
        include_once($mailerFile);
        $conf= $GLOBALS['fw_config']->get('mail_server');
        $this->mailer =& new PHPMailer;
        $this->mailer->Host     = $conf['host'];
        $this->mailer->Mailer   = $conf['mailer'];
        $this->mailer->SMTPAuth = $conf['smtpAuth'];
        $this->mailer->CharSet  = $conf['charSet'];
        $this->mailer->Encoding = $conf['encoding'];
        $this->mailer->From     = $conf['from'];
        $this->mailer->Username = $conf['username'];
        $this->mailer->Password = $conf['password'];
        $this->mailer->IsSMTP();
    }

    
    function send($address, $name='', $subject, $content, $formName=null, $debug=false){
        $this->mailer->SMTPDebug = $debug;
        $subject = $subject;
        $this->mailer->Subject = $subject;
        $this->mailer->Body = textFormat($content);
        $this->mailer->AltBody = $content;
        if($formName) $this->mailer->FromName = $formName;
        $this->mailer->AddAddress($address, $name);
        $result = $this->mailer->Send() ? true : false;
        $this->mailer->ClearAddresses();
        return $result;
    }

    
    function sendMore($address, $subject, $content){
        return false;
    }
}
?>
