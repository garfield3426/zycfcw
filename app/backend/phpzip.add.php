<?php
/*-----------------------------------------------------+
 * 上传专题页
 *
 * @author maorenqi 
 +-----------------------------------------------------*/
include_once( LIB_DIR.'/zip.class.php' );//加载phpzip类
//include_once(LIB_DIR.'/category.class.php');
class Add extends Page{
    var $db;  
    function __construct(){
        parent::__construct();
       $this->db = getDb();
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
        if($_FILES['news_img']['name']){
			$path = 'zt/';
			if( !file_exists($path) ){								// 检查保存附件的路径是否存在
			    chmod( $path, 0777 );
			}//END IF
			//允许上传的文件格式
		
			$tp = array("application/x-zip-compressed");
			//检查上传文件是否在允许上传的类型
			$max = 10240000;
			
			if($_FILES["news_img"]["size"]>$max){
				phpBox('文件过大,请上传小于10M的文件');
				gotoPage(h);
			    exit;
			}
			
			if(!in_array($_FILES["news_img"]["type"], $tp)){
				phpBox('格式不对,请转换成ZIP格式！');
				gotoPage(h);
			    exit;
			}//END IF
			
		    $aFile1 = explode('.', $_FILES["news_img"]["name"]);
		  
		    //echo $_FILES["news_img"]["name"];//test.zip
		    // print_r($_FILES['news_img']);//Array ( [name] => test.zip [type] => application/x-zip-compressed [tmp_name] => /tmp/phpyfftcL [error] => 0 [size] => 219 )
		    //如果存在同名文件，则删除该文件
		    $file = $path.$aFile1[0];
		    if(file_exists($file)){
			   $this->deldir($file);//如果ZT里的存在该文件夹，则先删除该文件夹
		    }
		
		    $file3=$path.$_FILES["news_img"]["name"];
		   
			// 将上传的文件移动到指定的位置存放
			$result = move_uploaded_file($_FILES["news_img"]["tmp_name"], $file3);//特别注意这里传递给move_uploaded_file的第一个参数为上传到服务器上的临时文件
			chmod( $file3, 0777 );
			//echo $_FILES["news_img"]["name"];
			if($result){
				$dir="zt/"; 			
				$zip=new Zip();
				
				if(($is_zip=$zip->unzip_main($dir,$_FILES["news_img"]["name"]))){
				  if ($is_zip==1){
				  	phpBox('文件上传成功！但存在同名文件');
				  	unlink($path. $_FILES["news_img"]["name"]);//删除ZIP文件
				  	gotoPage(h);
			    	exit;
				     }
				  else {
				  	phpBox('文件上传并解压成功！');
				  	unlink($path. $_FILES["news_img"]["name"]);
				  	gotoPage(h);
			    	exit;
				  }
				}else {
					phpBox('文件错误，解压失败！');
					unlink($path. $_FILES["news_img"]["name"]);
				    gotoPage(h);
			    	exit;
				}
			
			}
		
		}
        Core::redirect(Core::getUrl('add'));
    }

    
    function export($item=null, $emsg=null){
        //页面输出的数据
        $pvar = array(
            'formAct' => Core::getUrl(),
        );
        $this->assign('v', stripQuotes($pvar));
        $this->addTplFile('form');
        $this->display();
    }

	function deldir($dir) {
		$dh=opendir($dir);
		while ($file=readdir($dh)) {
			if($file!="." && $file!="..") {
			  $fullpath=$dir."/".$file;
			  if(!is_dir($fullpath)) {
			      unlink($fullpath);
			  } else {
			      $this->deldir($fullpath);
			  }
			}
		}
		
		closedir($dh);
		if(rmdir($dir)) {
			return true;
		} else {
			return false;
		}
	}
}
?>
