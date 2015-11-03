<?php
/***************************************************************
 * 图片文件上传
 * 
 * @author yeahoo2000@163.com 
 ***************************************************************/
class PicUpload {
	var $_fileData;
	var $_uploadPath;
	var $_filePath;
	var $_fileName;

	function PicUpload($fileData, $uploadPath) {
		/*
		echo '<br>file_name:　　'.$fileData['name'];
		echo '<br>file_type:　　'.$fileData['type'];
		echo '<br>file_size:　　'.$fileData['size'];
		echo '<br>file_tmp_name:'.$fileData['tmp_name'];
		echo '<br>upload_path:'.$uploadPath; /**/
		
		$this->_fileData= $fileData;
		$this->_uploadPath= $uploadPath;
	}
	function _makeFilename() {
		switch ($this->_fileData['type']) {
			case 'image/pjpeg' :
				$fileType= '.jpg';
				break;
			case 'image/jpeg' :
				$fileType= '.jpg';
				break;
			case 'image/gif' :
				$fileType= '.gif';
				break;
			default :
				$fileType= '';
		}
		$this->_filePath= $this->_uploadPath.date('y/m/d').'/'.uniqid('').$fileType;
	}
	function upload() {
		$this->_makeFilename();
		if(!makedir($this->_filePath)){
			return false;
		}
		return move_uploaded_file(stripQuotes($this->_fileData['tmp_name']), $this->_filePath);
	}
	function getFilePath() {
		return $this->_filePath;
	}
}
?>