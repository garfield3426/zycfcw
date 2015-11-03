$(document).on('click', '.delete',function (){
		var r=confirm("确定要删除此文件？");
		if (r==true)
		{
			var imgpath = $(this).parent("li").find("input").val();   
			var str= new Array();
			str=imgpath.split("/");
			alert("http://www.zycfcw.com/index-images-delete-path-"+str[str.length-1]+".html");
			$.ajax({
				url: "http://www.zycfcw.com/index-images-delete-path-"+str[str.length-1]+".html",
				data:{},
				type: "GET",
				dataType:'json',
				success:function(data) {
					alert("删除成功！");
				},
				error:function(er)
				{
					alert(er);
				}
			});
			$(this).parent("li").remove();
		}
});