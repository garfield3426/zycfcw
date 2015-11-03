<?php
/***************************************************************
 * 上传图片
 * 
 * @author maorenqi
 ***************************************************************/
class Upload extends Page{
    var $db;
    var $tab = 'album_photo';
   
    /**
     * 构造方法
     */
    function __construct(){
        parent::__construct();
        $this->tab = $this->conf->get('table_prefix').$this->tab;
       
        $this->db = getDb();
        //$this->db->debug = true;
    }
    
    function process(){

        $this->upload();
    }
    
    //上传
    function upload(){
  
			// Code for Session Cookie workaround
			if (isset($_POST["PHPSESSID"])) {
				session_id($_POST["PHPSESSID"]);
			} else if (isset($_GET["PHPSESSID"])) {
				session_id($_GET["PHPSESSID"]);
			}
		
			session_start();
		
			$POST_MAX_SIZE = ini_get('post_max_size');
			$unit = strtoupper(substr($POST_MAX_SIZE, -1));
			$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));
		
			if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
				header("HTTP/1.1 500 Internal Server Error");
				echo "POST exceeded maximum allowed size.";
				exit(0);
			}
		
		// Settings
			$save_path =  "SWFUpload/file/";
			$upload_name = "Filedata";
			$max_file_size_in_bytes = 2147483647;				// 2GB in bytes
			$extension_whitelist = array("doc", "txt", "jpg", "gif", "png");	//允许的文件
			$valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';			//允许的文件名字符
			
		// Other variables	
			$MAX_FILENAME_LENGTH = 260;
			$file_name = "";
			$file_extension = "";
			$uploadErrors = array(
		        0=>"文件上传成功",
		        1=>"上传的文件超过了 php.ini 文件中的 upload_max_filesize directive 里的设置",
		        2=>"上传的文件超过了 HTML form 文件中的 MAX_FILE_SIZE directive 里的设置",
		        3=>"上传的文件仅为部分文件",
		        4=>"没有文件上传",
		        6=>"缺少临时文件夹"
			);
		
			if (!isset($_FILES[$upload_name])) {
				HandleError("No upload found in \$_FILES for " . $upload_name);
				exit(0);
			} else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
				HandleError($uploadErrors[$_FILES[$upload_name]["error"]]);
				exit(0);
			} else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
				HandleError("Upload failed is_uploaded_file test.");
				exit(0);
			} else if (!isset($_FILES[$upload_name]['name'])) {
				HandleError("File has no name.");
				exit(0);
			}
			
			$file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
			if (!$file_size || $file_size > $max_file_size_in_bytes) {
				HandleError("File exceeds the maximum allowed size");
				exit(0);
			}
			
			if ($file_size <= 0) {
				HandleError("File size outside allowed lower bound");
				exit(0);
			}

			$file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES[$upload_name]['name']));
			if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
				HandleError("Invalid file name");
				exit(0);
			}

			if (file_exists($save_path . $file_name)) {
				HandleError("File with this name already exists");
				exit(0);
			}
		
		// Validate file extension
			$path_info = pathinfo($_FILES[$upload_name]['name']);
			$file_extension = $path_info["extension"];
			$is_valid_extension = false;
			foreach ($extension_whitelist as $extension) {
				if (strcasecmp($file_extension, $extension) == 0) {
					$is_valid_extension = true;
		
					break;
				}
			}
			if (!$is_valid_extension) {
				HandleError("Invalid file extension");
				exit(0);
			}
		    
			$file_name = mktime().'_'.$file_name;   
			if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$file_name)) {
				HandleError("文件无法保存.");
				exit(0);
			}/*else{
		mysql_query("Insert into {$this->tab} (id,title,album_id) values (NULL,'$file_name',)");
			}*/
		
			echo "File Received";
			exit(0);
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
    
    function HandleError($message) {
		header("HTTP/1.1 500 Internal Server Error");
		echo $message;
	}
}

?>