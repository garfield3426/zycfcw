var $_GET = [];         //解析页面Get参数
var $_JS_LOADED = [];   //已载入的JavaScript文件
init();                 //执行初始化



//初始化
function init(){
    var aParams = document.location.search.substr(1).split('&');
    for (i=0; i < aParams.length; i++){
        var aParam = aParams[i].split('=');
        $_GET[aParam[0]] = aParam[1];
    }
}

/* 
 * 动态加载JavaScript文件
 * @param string src 文件URL
 * @return null
 */
function $import(src){
    if(!$_JS_LOADED[src]){
        try{
            var xhr = window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
            xhr.open("GET", src, false);
            xhr.send(null);

            if(200 == xhr.status || 0 == xhr.status){
                if (window.execScript)
                    window.execScript(xhr.responseText);
                else window.eval.call(window, xhr.responseText);
            }
            $_JS_LOADED[src] = true;
        }catch(e){
            alert('系统错误: 加载文件"' + src + '"出错! \r\n[在第 ' + e.lineNumber + ' 行出现 ' + e.message + ' 错误]');
        }
    }
}

//购物车代码
var shoppingCart = null;
function showShoppingCart(src){
    width = 500;
    height = 280;
    win=window.open(src,'ShoppingCart','width='+width+','+'height='+height+',resizable=1,scrollbars=yes');
    w=screen.width;
    h=screen.height;
    w= w-width-50;
    h= h-height-120;
    win.moveTo(w,h);
    return win;
}
function addItem(src){
    if(shoppingCart && !shoppingCart.closed){
        shoppingCart.location.replace(src);
    }
    else{
        shoppingCart = showShoppingCart(src);
    }
    shoppingCart.focus();
}
