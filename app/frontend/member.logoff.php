<?php

class Logoff extends Action{
    var $AuthLevel = ACT_OPEN;
    function process(){
        $this->sess->end();
        Core::redirect('/index-member-login.html');
    }
}
?>
