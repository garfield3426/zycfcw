$import('js/dimensions.js');
$import('js/ui/datepicker.js');
$import('js/ui/datepicker-zh-CN.js');
$import('js/ui/pagination.js');
$import('js/ui/cluetip.js');
var ui = {
    //初始化
    init: function(){
        if($('select.auto')) ui.selector($('select.auto'));
        if($('input:text.auto')) ui.dateInput($('input:text.auto'));
        if($('input:checkbox.auto')) ui.permissionsCheckbox($('input:checkbox.auto'));//权限选择
        if($('a.auto')) ui.linkAction($('a.auto'));//自动为需要的链接加上信息提示
        if($('input:button.auto')) ui.linkAction($('input:button.auto'));
        if($('.pagination.auto')) ui.pagination($('.pagination.auto'));//自动加载分页按钮
        if($('.state.auto')) ui.stateImg($('.state.auto'));//自动替换状态图片
        if(jsonData.emsg) ui.errorMsg(jsonData.emsg);
    },
    permissionsCheckbox : function(obj){
	obj.each(function(){
            var data = jsonData.permissions[$(this).attr('value')];
            if(data) $(this).attr('checked', 'checked');
        });
    },
    //选择器
    selector : function(obj){
        obj.each(function(){
            var data = jsonData[$(this).attr('name')];
            if(!data) return;
            if(!data.list) return;
            //如果不使用,则换成hidden add 2008-03-26
            var listCount = 0;
            for(var k in data.list){
                if(data.display == 1) listCount+=10;
                if(k.length)listCount++;
            }
            if(listCount < 2){
                var i = $('<input type="hidden">');
                $(i).attr('name', $(this).attr('name')).val(data.value);
                $(this).parent().html('').hide().append(i);
                return;
            }
            if(data.id){
                $(this).attr('id', data.id);
            }
            if(data.name){
                $(this).attr('name', data.name);
            }
            if(data.size){
                $(this).attr('size', data.size);
            }else{
                $(this).attr('size', 1);
            }
            if(data.disabled){
                $(this).attr('disabled', data.disabled);
            }
            for(var k in data.list){
                var op = $('<option>');
                if(k == data.value){
                    $(op).attr('selected', 'selected');
                    $(op).val(k);
                    $(op).append(data.list[k]);
                }else if(0 <= k.indexOf('disabled_')){
                    $(op).attr('disabled', 'disabled');
                    $(op).val(k);
                    $(op).append(data.list[k]);
                }else{
                    $(op).val(k);
                    $(op).append(data.list[k]);
                }
                $(this).append(op);
                $(this).bind('change', function(){
                    if(0 <= $(this).find('[selected]').val().indexOf('disabled_')){
                        $(this).children().removeAttr('selected').eq(0).attr('selected', 'selected');;
                    }
                });
            }
            //绑定事件
            if(data.action){
                switch(data.action.type){
                    //多选操作
                    case 'mulopChoose':
                    $(this).bind('change', function(){
                        var v = $(this).find('[selected]').val();
                        if(data.action[v] && data.action[v]['msg']){
                            if(confirm(data.action[v]['msg'])){
                                $('#mainForm').attr('action', data.action[v]['url']).submit();
                            }
                        }
                    });
                    break;
                    //行选择器
                    case 'rowsChoose':
                    $(this).bind('change', function(){
                        var v = $(this).find('[selected]').val();
                        var l = data.action['url'];
                        var len = l.length;
                        
                        l = l.substr(0,len-5); 
                       
                        
                        if(-1 == l.indexOf(',')){
                            if(l.indexOf("&row=")>=0){
                            	
                                l = l.replace(/-row-[-0-9]{0,}/,   "-row-" + v );
                                l = l + ".html";
                            }else{
                            	
                                l +="-row-" + v + ".html";
                            }
                            if(l.indexOf(",page,")>=0){
                            	
                                l = l.replace(/&page=[-0-9]{0,}/,   "&page=0");
                            }
                        }else{
                        	
                            if(l.indexOf(",row,")>=0){
                                l = l.replace(/,row,[-0-9]{0,}/,   ",row," + v);
                            }else{
                                l +=",row," + v;
                            }
                            if(l.indexOf(",page,")>=0){
                                l = l.replace(/,page,[-0-9]{0,}/,   ",page,0");
                            }
                        }
                        document.location.replace(l);
                    });
                    break;
                    //禁选项(可带信息提示,单个或多个)
                    case 'cannotChoose':
                    $(this).bind('change', function(){
                        var v = $(this).find('[selected]').val();
                        if(v.indexOf('disabled_')>=0){
                            $(this).children().removeAttr('selected').eq(0).attr('selected', 'selected');
                            if(data.action['msg'])alert(data.action['msg']);
                        }
                    });
                    break;
                }
            }
        });
    },
    //日期输入框
    dateInput : function(obj){
        obj.each(function(){
            var data = jsonData[$(this).attr('name')];
            if(!data) return;
            if(data.id){
                $(this).attr('id', data.id);
            }
            if(data.name){
                $(this).attr('name', data.name);
            }
            if(data.value){
                $(this).val(data.value);
            }
            if(data.size){
                $(this).attr('size', data.size);
            }else{
                $(this).attr('size', 10);
            }
            if(data.disabled){
                $(this).attr('disabled', data.disabled);
            }
            if(data.dateFormat){
                var dformat = data.dateFormat;
            }else{
                var dformat = 'yy-mm-dd';
            }
            $(this).datepicker({dateFormat:dformat, speed:0});
        });
    },
    //链接处理(可带信息提示)
    linkAction : function(obj){
        obj.each(function(){
            var key = $(this).attr('title');
            if(jsonData[key]){
                switch($(this).attr('type')){
                    //按钮
                    case 'button':
                        $(this).bind('click', function(){
                            if(jsonData[key]['msg']){
                                if(!confirm(jsonData[key]['msg'])) return;
                            }
                            var link = jsonData[key]['url'];
                            
                            if($(this).attr('ext')){
                                if(-1 == link.indexOf(',')){
                                    document.location.replace(link + '-id-' + $(this).attr('ext') + '.html');
                                }else{
                                    document.location.replace(link + ',id,' + $(this).attr('ext') + '.html');
                                }
                            }else{
                                document.location.replace(link);
                            }
                        });
                    break;
                    //A标签
                    default:
                    if(-1 == jsonData[key]['url'].indexOf(',')){
                        var link = jsonData[key]['url'].replace(/&id=[0-9]{0,}/, '');
                        if($(this).attr('target')){
                            $(this).attr('href', link + '-id-' + $(this).attr('href') + '.html')
                            return;
                        }
                        $(this).attr('val', $(this).attr('href')).attr('href', '#');
                        $(this).bind('click', function(){
                            if(jsonData[key]['msg']){
                                if(!confirm(jsonData[key]['msg'])) return;
                            }
                            document.location.replace(link + '-id-' + $(this).attr('val') + '.html');
                        });
                    }else{
                        var link = jsonData[key]['url'].replace(/,id,[0-9]{0,}/, '');
                        if($(this).attr('target')){
                            $(this).attr('href', link + ',id,' + $(this).attr('href'))
                            return;
                        }
                        $(this).attr('val', $(this).attr('href')).attr('href', '#');
                        $(this).bind('click', function(){
                            if(jsonData[key]['msg']){
                                if(!confirm(jsonData[key]['msg'])) return;
                            }
                            document.location.replace(link + ',id,' + $(this).attr('val'));
                        });
                    }
                }
                $(this).removeAttr('title');
            }
        });
    },
    //输出状态标
    stateImg : function(obj){
        if(!obj) return;
        obj.each(function(){
            switch($(this).attr('src')){
                case '-1':
                    $(this).attr('src', 'backend/i/close.gif');
                    break;
                case '0':
                    $(this).attr('src', 'backend/i/unallowed.gif');
                    break;
                case '1':
                    $(this).attr('src', 'backend/i/allow.gif');
                    break;
                default:
                    $(this).attr('src', 'backend/i/warning.gif');
            }
        });
    },
    //输出错误信息
    errorMsg : function(obj){
        if(!obj) return;
        for(var key in obj){
            if(!document.getElementsByName(key).length){
                alert('[error]errorMsg(): id has not ' + key);
            }else{
                var renderTo = $(document.getElementsByName(key));
                renderTo.each(function(){
                    var img = $('<img>');
                    $(img).attr('title', obj[key]);
                    $(img).attr('src', 'backend/i/error.gif');
                    $(img).cluetip({splitTitle: '|', cluetipClass: 'errorMsg', dropShadow: false});
                    $(this).after(img);
                });
            }
        }
    },
    //分页按钮
    pagination : function(obj){
        obj.each(function(){
            var data = jsonData['pagination'];
            if(!data) return;
            var total = data.total-0;
            var row = data.row-0;
            var currentPage = data.currentPage-0;
            var numDisplayEntries = data.numDisplayEntries-0;
            var numEdgeEntries = data.numEdgeEntries-0;
            var prevShowAlways = data.prevShowAlways-0;
            var nextShowAlways = data.nextShowAlways-0;
            $(this).pagination(total, {
                items_per_page: row,
                current_page: currentPage,
                num_display_entries: numDisplayEntries,
                num_edge_entries: numEdgeEntries,
                link_to: data.linkTo,
                prev_text: data.prevText,
                next_text: data.nextText,
                ellipse_text: data.ellipseText,
                prev_show_always: prevShowAlways,
                next_show_always: nextShowAlways,
                callback: function(page_id, jq){
                	var urllen=data.url.length;
                	var url1=data.url.substr(0,urllen-5);
                	 
                    if(data.url.indexOf("&page")>=0){
                        var url = url1.replace(/-page-[-0-9]{0,}/,   "-page-" + page_id +  "-");
                        url=url+'.html';
                    }else{
                        var url = url1 + "-page-" + page_id+".html";
                    }
                  
                    document.location.replace(url);
                }
            });
        });
    }
}
