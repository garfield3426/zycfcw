<?php
/*
 *
 *    php生成数字和小写字母的验证码图片
 *    Maxwin@lilybbs  2006.5 
 *    自己根据实际情况修改字体及颜色
 *    
 */

 
/*
 *
 *    生成波纹，网上找的，稍微修改了一下。添加了x方向的波动
 *    可以自行修改 $i 的步长、grade来达到不同的效果
 *
 */
function wave_region($img, $x, $y, $width, $height,$grade)
{

   for ($i=0;$i<$width;$i+=2)
   {
       imagecopy($img,$img,
           $x+$i-2,$y+sin($i/10)*$grade,    //dest
           $x+$i,$y,            //src
           2,$height);
   }
   for ($i=0;$i<$height;$i+=2)
   {
       imagecopy($img,$img,
           $x+sin($i/20)*$grade,$y+$i-2,    //dest
           $x,$y+$i,            //src
           $width,2);
   }
}

//生成随机数
function mystr($length)
{
    $str = '';
    srand(microtime() * 100000);//据说php4.2以后不是必须的了
    $str = '';
    for ($i = 0; $i < $length; $i++)
    {
        $rand = rand(0,35);
        if ($rand < 10)
            $str .= $rand;
        else
            $str .= chr($rand + 87);
    }
    return $str;
}

//分离颜色RGB
function GetRValue($col)
{
    return hexdec(substr($col, 1, 2));
}

function GetGValue($col)
{
    return hexdec(substr($col, 3, 2));
}

function GetBValue($col)
{
    return hexdec(substr($col, 5, 2));
}

//////////////////////////////////////////////////////////////

function create_code(){
    $basePath = dirname(__FILE__);

    header("Content-type: image/png");
    //系统字体路径
    $fontPath = $basePath.'\\fonts\\';
    //定义几个要使用的字体
    $font = array ("comic.ttf", "cour.ttf", "ariblk.TTF");
    $fontcolor = array("#EE3B3B", "#E066FF", "#B452CD", "#FF9900", "#009900");
    //$fontColor = ar
    $fontsize = 20;    //字体大小
    $strLength = 4;    //验证码位数
    $str = mystr($strLength);

    //生成图片
    $im_w = $fontsize * $strLength + 10;
    $im_h = $fontsize * 1.8;
    $im = imagecreate($im_w, $im_h);

    //added<<
    //$rnd = rand(1,8);
    //$im = imagecreatefromjpeg($basePath."\\background\\background".$rnd.".jpg");
    //added>>

    //背景颜色
    //imagecolorallocate($im, 255,255,255);
    imagecolorallocatealpha($im, 255, 255, 255, 100);//使用透明背景

    $col = $fontcolor[rand(1, count($fontcolor)) - 1];
    $color = imagecolorallocate($im, GetRValue($col), GetGValue($col), GetBValue($col));
    imagettftext($im, $fontsize, 0, 5, $fontsize*1.3, $color, $fontPath . $font[rand(1, count($font)) - 1], $str);
    wave_region($im, 0, 0, $im_w, $im_h, 6);
    imagepng($im);
    imagedestroy($im);

    return $str;
}
