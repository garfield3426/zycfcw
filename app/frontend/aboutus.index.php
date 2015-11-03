<?php


include_once(LIB_DIR.'/spyc.php');
class Index extends Page{
    var $AuthLevel = ACT_OPEN;
    
    function process(){
    	$pvar = array(
    		'about' => Spyc::YAMLLoad('spyc.yaml.php'),
            //search form Action
            'formAct' => Core::getUrl('showlist', 'article', array('page'=>0), true,true),
            //手术类型
            'bespeak_type' => unserialize(CLIENT_TYPE),     
        );
        //设定模板使用变量
        $this->assign('v', stripQuotes($pvar));
        $this->display();
    }
    
}
?>