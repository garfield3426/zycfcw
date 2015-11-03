lastScrollY=0;
function heartBeat(){ 
var diffY;
if (document.documentElement && document.documentElement.scrollTop)
diffY = document.documentElement.scrollTop;
else if (document.body)
diffY = document.body.scrollTop
else
{/*Netscape stuff*/}
//alert(diffY);
percent=.1*(diffY-lastScrollY); 
if(percent>0)percent=Math.ceil(percent); 
else percent=Math.floor(percent); 
document.getElementById("lovexin14").style.top=parseInt(document.getElementById
("lovexin14").style.top)+percent+"px";
lastScrollY=lastScrollY+percent; 
//alert(lastScrollY);
}
suspendcode12="<DIV id=\"lovexin12\" style='left:2px;POSITION:absolute;TOP:120px;'>ad1</div>"
suspendcode14="<DIV id=\"lovexin14\" style='right:2px;POSITION:absolute;TOP:70px;'><div class=\"help_ul\"><a href=\"http://chat.53kf.com/company.php?arg=pr-021&style=1\" target=\"_blank\"></a><a href=\"http://wpa.qq.com/msgrd?v=3&uin=1172899070&site=qq&menu=yes\" target=\"_blank\"></a><a href=\"/index-yuyue.html\"></a><a href=\"/index-client.html\"></a><a href=\"msnim:chat?contact=pr-021@hotmail.com\"></a></div></div>"
document.write(suspendcode14); 
window.setInterval("heartBeat()",1);
