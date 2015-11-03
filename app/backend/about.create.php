<?php
/*-----------------------------------------------------+
 * 发布设备信息
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
include_once(LIB_DIR.'/spyc.php');
class Create extends Page{
   
    
    
    function __construct(){
        parent::__construct();
        
    }

    
    function process(){
        //检查是否提交数据
        if(!empty($this->input['submit'])){
            $this->submit();
            return;
        }
        //输出页面
        $this->export();
    }

    
    function submit(){
        //获取提交的数据
        $item1 = stripQuotes(trimArr($this->input['text1']));
        $item2 = stripQuotes(trimArr($this->input['text2']));
       
        $item = array_combine($item1,$item2);
         
        $yaml = Spyc::YAMLDump($item);//写入数据
        file_put_contents('spyc.yaml.php',$yaml);//把数据写入spyc.yaml.php文件

        Core::redirect(Core::getUrl(''));
    }

    
    function export($item=null, $emsg=null){
    	$yaml = Spyc::YAMLLoad('spyc.yaml.php');//读取spyc.yaml.php里的数据
    	
        //页面输出的数据
        $pvar = array(
            //'item' => $item,
            //'jsonData' => $this->getJsonData($item, $emsg),
            'title' => $this->lang->get('p_about_createTitle'),
            'yaml' => $yaml,

            'formAct' => Core::getUrl(),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->addTplFile('form');
        $this->display();
    }
   
}
?>
