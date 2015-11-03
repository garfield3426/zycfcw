<?php
/***************************************************************
 * 保存网上求医
 * 
 * @author maorenqi
 ***************************************************************/
//require_once LIB_DIR.'/category.class.php';
class Save extends Page{
    var $AuthLevel = ACT_OPEN;
    var $db;
    var $tab = 'client';

    /**
     * 构造方法
     */
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->db = getDb();
        //$this->db->debug = true;
    }

    /**
     * 程序入口
     */
    function process(){
        $item = trimArr($this->input['item']);
        $item['title'] = htmlspecialchars(trim($item['title']));
        $item['name'] = htmlspecialchars(trim($item['name']));
        $item['content'] = htmlspecialchars(trim($item['content']));
        //检查提交数据
        $this->validate($item);
        
        if(!$this->insertDb($item)){
            PhpBox('留言失败，请重新输入信息！');
            gotoPage(h);
            exit;
        }else{
            PhpBox("留言成功！");
            gotoPageNull(h);
            exit;
        }
    }

    /**
     * 写入数据
     */
    function insertDb($i){
    	
        $i['put_time'] = time();
        if(!$i['pic']){
            $i['pic'] = $i['sex']==1 ? 'i/guestbook/m.gif' : 'i/guestbook/f.gif';
        }
        
        $i['state'] = 1;
        $i['h_id'] = CONTENT_SKY;

        $sql = "SELECT * FROM {$this->tab} WHERE id=-1";
        $rs = $this->db->Execute($sql);
        $sql = $this->db->GetInsertSQL($rs,$i);
        return $this->db->Execute($sql);
    }

    /**
     * 检查用户输入
     */
    function validate($i){
        if($i['code']!=$this->sess->get('code')){
    		PhpBox('验证码错误!');
    		GotoPage(h);
    		exit;
    	}
    	if(!$i['title']){
            PhpBox('请输入咨询主题!');
    		GotoPage(h);
    		exit;
        }
        if(strlen($i['title'])>200){
            PhpBox('咨询主题长度请限制在100个字以内!');
    		GotoPage(h);
    		exit;
        }
        if(!$i['name']){
            PhpBox('请输入你的姓名!');
    		GotoPage(h);
    		exit;
        }
        if(strlen($i['name'])>15){
            PhpBox('姓名长度请限制在5个字以内!');
    		GotoPage(h);
    		exit;
        }
        if(!$i['age']){
            PhpBox('请输入你的年龄!');
    		GotoPage(h);
    		exit;
        }

        
        if(!$i['tel']){
            PhpBox('请输入你的联系方式!');
    		GotoPage(h);
    		exit;
        }
        
        if(!$i['content']){
            PhpBox('请输入内容!');
    		GotoPage(h);
    		exit;
        }
        if(strlen($i['content'])>600){
            PhpBox('内容请限制在200个字以内!');
    		GotoPage(h);
    		exit;
        }
    	
    }
    
}
?>
