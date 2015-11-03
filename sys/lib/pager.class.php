<?php
/***************************************************************
 * ��ҳ����
 * 
 * @author yeahoo2000@163.com
 * @editor Try.Shieh@gmail.com
 ***************************************************************/
/**
 * �������ݷ�ҳ����
 * @param int $totalRecord �ܼ�¼��
 * @param int $currentPage ��ǰҳ
 * @param int $numPrePage ÿҳ��ʾ�ļ�¼��
 * @param int $half ������͢�����������,Ĭ��Ϊ5��
 * @return array
 */
class Pager{  
    function index($totalRecord, $currentPage, $numPrePage, $half=3) {
        $totalPage= $numPrePage > $totalRecord ? 1 : $totalRecord % $numPrePage ? (int)($totalRecord / $numPrePage) + 1 : (int)($totalRecord / $numPrePage);
        $currentPage = ($currentPage >0 && $currentPage < $totalPage) ? (int)$currentPage : 0;
        if($totalPage > $half*2 && ($currentPage >$half)){
            if(($currentPage+$half)<$totalPage){
                $j=$currentPage+$half+1;
                $i=$currentPage-$half;
            }
            else {
                $j=$totalPage;
                $i=$currentPage-($half * 2 -($j-$currentPage));
            }
        }else {
            $i = 0;
            $j = $totalPage>$half * 2+1?$half*2+1:$totalPage;
        }
        $data['list'] = array();
        for (; $i<$j; $i++) {
            if($i == $currentPage) $data['current'] = $i+1;
            $data['list'][($i+1)] = Core::getUrl('', '', array_merge($_SESSION['query_data'], array('page'=>$i)),'',true);
        }
        if($currentPage > 0) $data['prev'] = Core::getUrl('', '', array_merge($_SESSION['query_data'], array('page'=>($currentPage -1))),'',true);
        if($currentPage +1 < $totalPage) $data['next'] =  Core::getUrl('', '', array_merge($_SESSION['query_data'], array('page'=>($currentPage +1))),'',true);
        $data['totalPage'] = $totalPage;
        return $data;
    }
}
?>
