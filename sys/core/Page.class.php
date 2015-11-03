<?php
/**
 * Page 类
 */
class Page extends Action {
	var $cacheTime = 0;
    var $pathAppend = null;
    var $_tplFile = array();
    var $_pagevar = array();
    var $_contents;
    var $_cacheFile;

    /**
     * 构造方法
     */
    function __construct() {
        parent::__construct();
        if($this->useContentPart){
            $this->input['part'] = $this->input['part']?(int)$this->input['part']:'0';
        }

        $this->_cacheFile = cache::cacheFilename(CURRENT_MODULE,CURRENT_ACTION,$this->input[$this->pathAppend],$this->input['part']);
        if(($this->cacheTime < 0 && file_exists($this->_cacheFile)) || (time() - @filemtime($this->_cacheFile)) <= $this->cacheTime) {
            include($this->_cacheFile);
            exit();
        }

    }

    function _compile() {
        extract($this->_pagevar);
        ob_start();
        foreach ($this->_tplFile as $file) {
            if($file['absolute']){
                $fileName = $file['filename'];
            }else{
                $fileName = APP_DIR.'/'.APP_ID.'/'.LANGUAGE.'/'.CURRENT_MODULE.'.'.$file['filename'].'.htm';
            }
            if(is_readable($fileName)){
                include($fileName);
            }else{
                trigger_error(_Error(0x040101, array(__FUNCTION__, $fileName)));
                Core::log(L_WARNING, _Error(0x040101, array(__FUNCTION__, $fileName)));
            }
        }
        $this->_contents = ob_get_contents();
        ob_end_clean();
        if ($this->cacheTime) {
            IO::writeFile($this->_cacheFile, $this->_contents);
        }
    }
    
    function addTplFile($filename, $absPath=false) {
        $this->_tplFile[]= array('filename' => $filename ,'absolute' => $absPath);
    }
    
    function assign($key, $var) {
        $this->_pagevar[$key] = $var;
    }

    public function csubstr($str,$start,$length,$suffix=true,$charset="utf-8")   
	{   
		$re['utf-8']  =  "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";   
		$re['gb2312']  =   "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";   
		$re['gbk']  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";   
		$re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";   
		preg_match_all($re[$charset],$str,$match);  
		if(count($match[0]) < ($length + $start - $start)) {
			return $str;
		}
		else {
 		 if(function_exists("mb_substr"))   
 			 return mb_substr($str,$start,$length,$charset);
 			 $slice = join("",array_slice($match[0],$start,$length)); 
 			 if($suffix) 
 				 return $slice."...";   
 				 return $slice;   
  		 } 
 	 }
    function fetch() {
        if(!count($this->_tplFile)){
            $this->_tplFile[] = array('filename' => CURRENT_ACTION, 'absoulte' => false);
        }
        $this->_compile($this->_pagevar);
        return $this->_contents;
    }

    function display() {
        echo $this->fetch();
    }
}
?>
