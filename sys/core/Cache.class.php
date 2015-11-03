<?php
/**
 * Cache ��
 * �ṩ����Ļ�������
 */
class Cache{
    /**
     * �õ���Ӧģ��Ͷ����ľ�̬����ҳ��·�����ļ���
     * @param string $module ģ����
     * @param string $action ������
     * @param string $uniqueId �����Ҫ����Ψһ�����ֱ𻺴�ɶ��ҳ��,�����ָ��Ψһ�ı�ʶ(ͨ����ʹ�ñ�����),��󳤶�12λ
     * @param string $part ����Ǹ�ҳ��ֳɶಿ�ݽ��л���,��Ҫָ��ĳһ���ݵı��,��󳤶�3λ
     * @return string �ļ�·��
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
     * �����ĳ���������ɵĻ����ļ�
     * @param string $module ģ����
     * @param string $action ������
     * @param string $uniqueId ����Ψһ�������ָ���Ļ����ļ�
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