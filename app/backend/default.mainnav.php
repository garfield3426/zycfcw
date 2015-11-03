<?php

class MainNav extends Page{
    var $AuthLevel = ACT_NEED_LOGIN;
    function process(){
        $this->display();
    }
}
?>
