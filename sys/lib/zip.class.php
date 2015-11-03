<?
class Zip
{

/**
* 创建用户数据存放目录，如果不存在，一级一级重新创建
* 创建wind文件夹例如 $path="D:\www\www.8noya.com\_Map/0-0/png/4/0-0.png"
* 创建linux文件夹例如 $path="/home/www/www.8noya.com/_Map/0-0/png/4/0-0.png"
* 
* @param   string   $path 带文件的文件夹名称
*/
private $zip;   //开的zip文件的全路径
private $dir;//保存该zip文件的路径 
private $unzip_fileName;//该zip文件解压以后的文件夹名 

function create_dir($path){ //创建目录
   $dirs=dirname($path);
   if(is_dir($dirs)) return ;
   $dirs = explode('/',$dirs);

   //判断操作系统，linux的话，去除第一空项
   if(strpos($path,'/')==0)
   {
     $dirpath = '/';
     unset($dirs[0]);
   }
   while (($directory = array_shift($dirs)))
   {
       $dirpath .=$directory.'/';
     if(!is_dir($dirpath))
     {
       mkdir($dirpath);
       if(!chmod($dirpath,0777))
       {
        die('mkdir fail');
       }
     }
   }
}

    function save_zip($savedir=""){ //保存zip文件 使用全路径
   if (empty($savedir)){
      $this->dir=$_SERVER['DOCUMENT_ROOT']."/xmwbg/resource/";
   }else {
      $this->dir=$savedir;
   }
   
   return $this->dir;
}

function open_zip($zipFile){    //打开zip文件 使用全路径 为了方便解压保存zip是同一个目录
   if (empty($zipFile)){
      return FALSE;
   } 
   $this->zip=zip_open($this->dir.$zipFile);
 
   return $this->zip;
}



   function unzip_fileName(){ //返回解压以后的目录名
   if($this->zip){    
     $zip_entry = zip_read($this->zip);     
     $unfile=zip_entry_name($zip_entry);
     if (substr($unfile,-1)=="/"){
      $unfile=substr($unfile,0,-1);
     }
    
     $this->unzip_fileName=$unfile;
     return $this->unzip_fileName;
   }else 
          return FALSE;
   
}

   function chk_dir(){     //检查该目录下是否还有其他同名文件
   $handle=opendir($this->dir);
   $fname =array();
   while (($file = readdir($handle))) {
          $fname[]=$file;
   }
   closedir($handle);
   for ($i=0;$i<count($fname);$i++){
    if($fname[$i]==$this->unzip_fileName){
     return TRUE;
    }
   }
   return FALSE;
   
}

function unzip(){    //解压函数

    if($this->zip){    
     while(($zip_entry = zip_read($this->zip))){     
             
      if(substr(zip_entry_name($zip_entry),-1) != "/" && zip_entry_open($this->zip,   $zip_entry,   "r")){
          if (!file_exists($this->dir.dirname(zip_entry_name($zip_entry)))){
             $this->create_dir($this->dir.zip_entry_name($zip_entry));
          }
             $buf   =   zip_entry_read($zip_entry,   zip_entry_filesize($zip_entry));
             $fout   =   fopen($this->dir.zip_entry_name($zip_entry),"wb+");
             fwrite($fout,$buf);
             zip_entry_close($zip_entry);
      }else{
            $this->create_dir($this->dir.zip_entry_name($zip_entry));
       }
       }
              
         
         zip_close($this->zip);
         return TRUE;
    }else 
         return FALSE; 
   
}

	function unzip_main($savedir,$zipFile){
	   if (!empty($zipFile)&&!empty($savedir)) {
	        $this->save_zip($savedir); 
	           $this->open_zip($zipFile);
	            
	    if ($this->unzip_fileName()){ 
	       if ($this->chk_dir()){
	         return 1; //检查该目录下有其他同名文件,不准解压。
	       }
	     if ($this->unzip()){
	      return $this->unzip_fileName;
	     }else {
	        return FALSE;
	     }
	    } 
	   }
	     return FALSE; 
	}
}

?>