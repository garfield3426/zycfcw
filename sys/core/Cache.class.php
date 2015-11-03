<?php
/**
 * Cache 类
 * 提供缓存的基本操作
 */
class Cache{
    /**
     * 得到相应模块和动作的静态缓存页的路径和文件名
     * @param string $module 模块名
     * @param string $action 动作名
     * @param string $uniqueId 如果是要根据唯一特征分别缓存成多个页面,则必须指定唯一的标识(通常可使用表主键),最大长度12位
     * @param string $part 如果是该页面分成多部份进行缓存,则要指定某一部份的编号,最大长度3位
     * @return string 文件路径
     */
    function cacheFilename($module, $action, $uniqueId = null, $part = null) {
        if(strlen($uniqueId)){
            $pa = sprintf('%12s', $uniqueId);
            $pa = str_replace(' ', '_', $pa);
            $pathAppend = '/'.$pa[0].$pa[1].$pa[2].'/'.$pa[3].$pa[4].$pa[5].'/'.$pa[6].$pa[7].$pa[8].'/'.$pa[9].$pa[10].$pa[11];
            if(strlen($part)){
                $pa = sprintf('%3s', $part);
                $pa = str_replace(' ', '_', $pa);
                $pathAppend .= '/'.$pa[0].$pa[1].$pa[2];
            }
        }else $pathAppend = '';
        return VAR_DIR.'/cache/'.LANGUAGE.'/'.$module.'_'.$action.$pathAppend.'.cache';
    }

    /**
     * 清除由某个动作生成的缓存文件
     * @param string $module 模块名
     * @param string $action 动作名
     * @param string $uniqueId 根据唯一特征清除指定的缓存文件
     */
    function removeCache($module, $action, $uniqueId = null) {
        if(is_array($uniqueId)){
            foreach($uniqueId as $v){
                $cacheFile = Cache::cacheFilename($module,$action,$v);
                IO::removeFile($cacheFile);
            }
        }else {
            $cacheFile = Cache::cacheFilename($module,$action,$uniqueId);
            IO::removeFile($cacheFile);
        }
    }
}
?>