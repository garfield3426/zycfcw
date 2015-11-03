<?php
/* ----------------------------------------------------+
 * 验证码
 * @author maorenqi
 +-----------------------------------------------------*/

class Code extends Page{
    public $AuthLevel = ACT_OPEN;

    function __construct(){
        parent::__construct();
    }

    function process(){
        $this->getCode();
    }

    
   
	public function getCode(){
		Header("Content-type: image/PNG");
		$im = imagecreate(58,18);
		$back = ImageColorAllocate($im, 245,245,245);	// 背景色
		imagefill($im,0,0,$back); //背景
		srand((double)microtime()*1000000);
		//生成4位数字
		for($i=0;$i<4;$i++){
			$font = ImageColorAllocate($im, rand(100,255),rand(0,100),rand(100,255));
			$authnum=rand(1,9);
			$vcodes.=$authnum;
			imagestring($im, 5, 2+$i*10, 1, $authnum, $font);
		}
		//验证码存入session
		$this->sess->set('code', $vcodes );	
		for($i=0;$i<100;$i++) { //加入干扰象素
			$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
			imagesetpixel($im, rand()%70 , rand()%30 , $randcolor);
		} 
//		echo $vcodes;
		ImagePNG($im);
		ImageDestroy($im);
	}
    
}
?>
