<?php
/**
 * IO 类
 * 提供文件系统的基本操作
 */
class Io {
     /**
     * 创建目录,可以创建父目录
     * (****注意:如果目录路径指向了某个文件,则会将这个文件删除****)
     * @param string $path 目录路径
     * @param int $mode 目录的读写属性
     */
    function mkdir($path, $mode= 0777){
        $path = dirname($path);
        if(file_exists($path)) return true;
        if(!preg_match('!^'.addslashes(ROOT).'/!', $path)){
            Core::log(L_WARNING, _Error(0x020101, array(__FUNCTION__, $path)));
            return false;
        }
        $path= preg_replace('!^'.addslashes(ROOT).'/!', '', $path);
        $dirstack= explode('/', $path);
        if(count($dirstack) > 50){
            Core::log(L_WARNING, _Error(0x020101, array(__FUNCTION__, $path)));
            return false;
        }
        $path= ROOT.'/';
        while($newdir= array_shift($dirstack)){
            if(is_file($path.$newdir)){	//如果这个路径指向的是文件,则删除
                @unlink($path.$newdir);
            }
            $path .= $newdir.'/';
            $stat= @mkdir($path, $mode);
        }
        return $stat;
    }

   /**
     * 写磁盘文件,会自动创建文件名中带的目录路径
     * @param string $file 文件名(可带路径)
     * @param string $text 文件内容
     * @param string $mode 写入模式
     *                      'r' 只读方式打开。
     *                      'r+' 读写方式打开。
     *                      'w' 写入方式打开。
     *                      'w+' 读写方式打开。
     *                      'a' 写入方式打开。
     *                      'a+' 读写方式打开。
     *                      'x' 创建并以写入方式打开。
     *                      'x+' 创建并以读写方式打开。
     *  @return bool
     * 
     */ 
    function writeFile($file, $text, $mode= 'w'){
        $oldmask= umask(0);
        $fp= @fopen($file, $mode);
        if(!$fp){
            if(!Io::mkdir($file) || !($fp= fopen($file, $mode))){
                trigger_error(_Error(0x020103, array(__FUNCTION__, $file)));
            }
        }
        fwrite($fp, $text);
        fclose($fp);
        umask($oldmask);
        return true;
    }

     /**
     * 递归删除指定目录下的所有文件和目录(也可作用于文件)
     * @param string $dirName 目录路径或文件路径
     * @return bool
     */
    function removeFile($dirName){
        $path = dirname($dirName);
        if(!preg_match('!^'.addslashes(ROOT).'/!', $path)){
            trigger_error(_Error(0x020104, array(__FUNCTION__, $path)));
        }
        if(is_file($dirName)){
            $result = @unlink($dirName)? true : false;
        }
        if(is_dir($dirName)){
            $handle= opendir($dirName);
            while(($file= readdir($handle)) !== false){
                if($file != '.' && $file != '..'){
                    $dir= $dirName.DIRECTORY_SEPARATOR.$file;
                    is_dir($dir) ? Io::removeFile($dir) : @unlink($dir);
                }
            }
            closedir($handle);
            $result= @rmdir($dirName) ? true : false;
            Core::log(L_WARNING, _Error(0x020105, array(__FUNCTION__, $dirName, $result)));
        }
        return $result;
    }
}
?>
