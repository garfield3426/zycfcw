<?php

header("expires:mon,26jul199705:00:00gmt");
header("cache-control:no-cache,must-revalidate");
header("pragma:no-cache");
class Header extends Page{
    var $AuthLevel = ACT_NEED_LOGIN;
    function process(){
        $link = array(
            'logoff' => Core::getUrl('logoff', 'default'),
            'about' => Core::getUrl('about', 'help'),
            'help' => Core::getUrl('help', 'help'),
            'profile' => Core::getUrl('profile', 'default')
        );
        $info = array(
            'username' => $this->sess->get('username'),
            'group' => $this->sess->get('groupTitle')
        );

        $this->assign('link', $link);
        $this->assign('info', $info);
        $this->display();
    }
}
?>
