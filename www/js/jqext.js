$.fn.menu = function(hl){
    return this.each(function(){
        $(this).find('ul').each(function(){
            $(this).hide();
            if(hl){ //高亮菜单项
                var expandParent = function(el){
                    $(el).show();
                    var p = $(el).parent().parent();
                    if( 'UL' == $(p).attr('tagName') && 'menu' != $(p).attr('class')){
                        expandParent(p);
                    }
                };
                var box = this;
                $(this).children('li').children('a').each(function(){
                    var link = $(this).attr('href');
                    if(-1 == link.indexOf('?')) return;
                    link = '\\' + link.substr(link.indexOf('?'));
                    var re = new RegExp(link, 'i');
                    if(document.location.search.match(re)){
                        $(this).css('font-weight', 'bold');
                       expandParent(box); 
                    }else{
                        if(-1 == link.indexOf(',')){
                            var re = new RegExp(link.substr(0, link.indexOf('&')));
                        }else{
                            var re = new RegExp(link.substr(0, link.indexOf(',')));
                        }
                        if(document.location.search.match(re)){
                           expandParent(box); 
                        }
                    }
                });
            }
            $(this).parent().children('a').click(function(){
                //$(this).parent().children('ul').DropToggleDown(300)
                $(this).parent().children('ul').toggle();
            });
        });
    });
}
$.winopen1 = function(	src		//窗口内容地址
,width	//宽度
,height	//高度
,s		//是否允许滚动条
){
	//弹出窗口并自动居中显示
	s = (s)?',resizable=1,scrollbars=yes':'';
	aa=window.open(src,'_blank','width='+width+','+'height='+height+s);
	b=screen.width;
	c=screen.height;
	b=(b-width)/2;
	c=(c-height)/2;
	aa.moveTo(b,c);
}


var winOpen = function(src, name, w, h, r){
    var l = (screen.width - w) / 2;
    var t = (screen.height - h) / 2;
    var r = r ? 'resizable=yes,scrollbars=yes' : 'resizable=no,scrollbars=no';
    var w = window.open(src, name, 'width=' + w + ',height=' + h + ',top=' + t + ',left=' + l + ',' + r);
    w.focus()
}

