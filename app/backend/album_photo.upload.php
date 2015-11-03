<?php
/***************************************************************
 * 上传图片
 * 
 * @author maorenqi
 ***************************************************************/
class Upload extends Page{
    var $db;
    var $tab = 'album_photo';
    var $tab_album = 'album';

    /**
     * 构造方法
     */
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
        $this->tab_album = $this->conf->get('table_prefix').$this->tab_album;
        $this->db = getDb();
        //$this->db->debug = true;
    }
    
    function process(){
        $this->upload();
    }
    
    //上传
    function upload(){
			$item = $this->buildImage();
			$item['id'] = $this->insertDb($item);
			if( !$item['id'] ){
			    $this->msg(false,'文件已上传,保存入数据库时出错!');
			}
			$this->msg(true,'上传成功!',$item);
    }
    
    //
    function buildImage(){
			 if (!isset($_FILES["Filedata"])) $this->msg(FALSE,'没有上传文件！');
			 //print_r( $_FILES["Filedata"] );
			 
	 		 $upload_file=$_FILES['Filedata']['tmp_name'];
	 		 $upload_file_name=$_FILES['Filedata']['name'];
		 	 $upload_file_size = $_FILES['Filedata']['size'];
	
			 $extend = pathinfo($upload_file_name);
			 $extend = strtolower($extend["extension"]);
			 $save_name = date('YmdHis',time()).rand(0,10000);
			 $save_file_name = "$save_name.$extend";
		 
		 	 $file_size_max =  100*1000*1000; //不限制大小 // 1000*1000;// 1M限制文件上传最大容量(bytes)
		 	 $store_dir = WEB_DIR.'/'.$this->conf->get('upload_dir').'/sys_album';// 上传文件的储存位置
		 	 $save_file = "$store_dir/$save_file_name";
		 	 $save_thum_file = "$store_dir/thum/$save_file_name";	
		 	 
		 	 // 检查文件类型
		 	 if ( !in_array($extend,array('gif','png','jpg','jpeg')) ) {
		 	 	   $this->msg(FALSE,"不允许的文件类型");
		 	 }
		 	 
		 	 // 检查文件大小
		 	 if ($upload_file_size > $file_size_max) {
		 	 	   $this->msg(FALSE,"对不起，你的文件容量大于规定");
		 	 }	
		 	 	//复制文件到指定目录
		 	 if (!move_uploaded_file($upload_file,$save_file)) {
		 	 		$this->msg(FALSE,"复制文件失败");
		 	 }
		 	 makeThumb($save_file, $save_thum_file,160,120);
		 	 return array(
		 	 		'url'=>$this->conf->get('upload_dir').'/sys_album/thum/'.$save_file_name,
		 	 		'title'=> $save_file_name
		 	 );	
    }
    
    /**
     * 写入数据
     */
    function insertDb($item){
        $item['put_time'] = time();
        $item['admin_id'] = $this->sess->getUserId();
        $item['path'] = $item['url'];  //默认状态为删除状态
        $item['state'] = 2;  //默认状态为删除状态
        $sql = "select * from {$this->tab} where id=-1";
        $sql = $this->db->GetInsertSQL($this->db->Execute($sql), $item);
        $this->db->Execute($sql);
        return $this->db->Insert_ID();
    }
    
    
    //
    function msg($success,$msg,$data=null){
    	echo json_encode(array('success'=>$success,'message'=>$msg,'data'=>$data));
    	exit();
    }
    

}


 function makeThumb($srcFile,$dstFile, $max_width, $max_height){
    $img_src = file_get_contents( $srcFile );
    $image = ImageCreateFromString( $img_src );

    $width = imageSx( $image );
    $height = imageSy( $image );

    $x_ratio = $max_width / $width;
    $y_ratio = $max_height / $height;

    if ( ($width <= $max_width) && ($height <= $max_height) ) {
        $tn_width = $width;
        $tn_height = $height;
    }else if (($x_ratio * $height) < $max_height) {
        $tn_height = ceil($x_ratio * $height);
        $tn_width = $max_width;
    }else{
        $tn_width = ceil($y_ratio * $width);
        $tn_height = $max_height;
    }

    /*生成高质量的缩略图方法*/
    $dst = imagecreatetruecolor($tn_width,$tn_height);//新建一个真彩色图像
    imagecopyresampled($dst, $image, 0, 0, 0, 0,$tn_width,$tn_height,$width,$height);

    ImageJpeg($dst, $dstFile);
    ImageDestroy($image);
    ImageDestroy($dst);

}





?>