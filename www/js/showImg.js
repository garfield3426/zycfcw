function getAbsolutePosX(obj) {
var l = obj.offsetLeft;
while(obj = obj.offsetParent)
l += obj.offsetLeft;
return l;
}

function getAbsolutePosY(obj) {
var t = obj.offsetTop;
while(obj = obj.offsetParent)
t += obj.offsetTop;
return t;
}

function genPannel(){
    oImgDiv=document.getElementById("imgL");
    oDiv=document.createElement("DIV");
    oDiv.id="oPanelLayer";
    oDiv.name="oPanelLayer";
    document.body.appendChild(oDiv);
}

function genPannelContent(){
    oDiv=document.getElementById("oPanelLayer");
    if(oDiv==null)return;
    str="<table align='left' cellspacing='0'><tr>";
    str+="<td onclick='prevFrame()' style='border-right:1px solid white;color:white;font-weight:bold;font-size:9pt;cursor:hand'>";
    str+="<";
    str+="</td>";
    if(lp>sImgArr.length-1)lp=0;
    if(lp<0)lp=0;
    j=lp;
    for(i=0;i<count;i++){
        str+="<td onclick='setImg("+j+")' id='cell"+j+"' align='center' width='"+sWidth+"px' style='border-right:1px solid white;color:white;font-weight:bold;font-size:9pt;cursor:hand'>";
        str+="<img title='"+sImgArr[j].desc+"' src='"+sImgArr[j].url+"' style='width:"+sWidth+"px;height:"+sWidth+"px'>";
        str+="</td>";
        j++;
        if(j>sImgArr.length-1)j=0;
    }
    str+="<td onclick='nextFrame()' style='border-right:1px solid white;color:white;font-weight:bold;font-size:9pt;cursor:hand'>";
    str+=">";
    str+="</td>";
    str+="</tr></table>";
    oDiv.innerHTML=str;
    //定位层的位置
    var leftPos;
    var topPos;
    leftPos=getAbsolutePosX(oImgDiv)+oImgDiv.offsetWidth-oDiv.offsetWidth;
    topPos=getAbsolutePosY(oImgDiv)+oImgDiv.offsetHeight-oDiv.offsetHeight;
    oDiv.style.cssText = "background-color:black;filter:alpha(opacity=95);position:absolute;left:"+leftPos+"px;top:"+topPos+"px;";  
    //设置图
    setImg(lp);
}

function nextFrame(){
    //下一帧
    lp++;
    genPannelContent();
}

function prevFrame(){
    //上一帧
    lp--;
    genPannelContent();
}

function setImg(indx){
    oImg=document.getElementById("img");
    try{
    oImg.filters.revealTrans.Transition=12;
    oImg.filters.revealTrans.apply();
    oImg.filters.revealTrans.play();
    }catch(e){}
    oImg.src=imgArr[indx].url;
}

function changeImg(){
    nextFrame();
}
function imgObj(oUrl,oLinkAddress,oDesc){
    this.url=oUrl;
    this.linkAddress=oLinkAddress;
    this.desc=oDesc;
}