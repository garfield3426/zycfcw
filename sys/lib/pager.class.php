<?php
/***************************************************************
 * 分页函数
 * 
 * @author yeahoo2000@163.com
 * @editor Try.Shieh@gmail.com
 ***************************************************************/
/**
 * 生成数据分页索引
 * @param int $totalRecord 总记录数
 * @param int $currentPage 当前页
 * @param int $numPrePage 每页显示的记录数
 * @param int $half 往左右廷伸的索引个数,默认为5个
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
