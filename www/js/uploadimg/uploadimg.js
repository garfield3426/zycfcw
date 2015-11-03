function uploadimg(a,b,c,d,e) {
    var img_id_upload=new Array();//初始化数组，存储已经上传的图片名
    var i=0;//初始化数组下标
    var x="";
    var type = null,size=null,buttonText=null,url=null;
    url='/zyc/uploads/';
    if(e=='photo'){
       type ='*.gif; *.jpg; *.png';
       size ='2000KB';
       buttonText='选择图片';
    }
    else if(e=='vedio')
    {
      type ='*.mp4; *.flv';
      size ='50000KB';
      buttonText='选择视频';
    }
    $(a).uploadify({
    	'auto'     :true,//关闭自动上传
    	'removeTimeout' : 1,//文件队列上传完成 1秒后删除
        'swf'      : '/js/uploadimg/uploadify.swf',
        'debug' :false,//调试模式
        'progressData':'percentage',//滚动条显示方式
        'uploader' : '/js/uploadimg/uploadify.php',//上传处理php
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
		'buttonText' : buttonText,//设置按钮文本
        'multi'    : true,//允许同时上传多张图片
        'width' : 130,
        'uploadLimit' : b,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'All Files',//Image Files只允许上传图像
        'fileTypeExts' : type,//限制允许上传的图片后缀
        'fileSizeLimit' : size,//限制上传的图片不得超过200KB 
        'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
             if(e=='photo'){
                  if(b==1)
                  {
                    $(c).html('<li><img width="110" height="110" src="'+url+eval(data)+'" /><a class="delete" >删除</a><input type="text" style=" display:none;" name="'+d+'" value="'+url+eval(data)+'" /></li>');
                  }
                  else
                  {
                    $(c).append('<li><img width="110" height="110" src="'+url+eval(data)+'" /><a class="delete" >删除</a><input type="text" style=" display:none;" name="'+d+'" value="'+url+eval(data)+'" /></li>');
    			  }
              }
              else if(e=='vedio')
              {
                 $(c).html('<li><img width="110" height="110" src="/zyc/vedio.png" /><a class="delete">删除</a><input type="text" style=" display:none;" name="'+d+'" value="'+url+eval(data)+'" /></li>'); 
              }
              img_id_upload[i]=data;
			  i++;
        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数
            alert("上传成功！");
            //if(img_id_upload.length>0)
            //alert('成功上传的文件有：'+encodeURIComponent(img_id_upload));
        }  
        
    });
}