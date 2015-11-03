<?php

class Logoff extends Action{
    var $AuthLevel = ACT_OPEN;
    function process(){
        $this->sess->end();
        Core::redirect(Core::getUrl($this->conf->get('action_default'), $this->conf->get('module_default')));
    }
}
?>
