<script type="text/javascript">
  $(function(){
        $("#KinSlideshow").KinSlideshow({
                moveStyle:"left", 		//设置切换方向为向下 [默认向左切换]
                intervalTime:5,   		//设置间隔时间为8秒  [默认为5秒]
                mouseEvent:"mouseover",		//设置鼠标事件为“鼠标滑过切换”  [默认鼠标点击时切换]
                titleFont:{TitleFont_size:14,TitleFont_color:"#fff"}, //设置标题文字大小为14px，颜色：#FF0000
				btn:{btn_bgColor:"#fff",btn_bgHoverColor:"#F27B00",
					  btn_fontColor:"#999999",btn_fontHoverColor:"#fff",btn_fontFamily:"Verdana",
					  btn_borderColor:"#fff",btn_borderHoverColor:"#F27B00",
					  btn_borderWidth:1,btn_bgAlpha:0.7}
        });
    })

</script>


<div id="KinSlideshow" style="visibility:hidden;">
	<?php $ad=ext('ad_list',5)?>
    <?php foreach ($ad['list'] as $i): ?>
  		<a href="<?php echo $i['url'];?>" target="_blank"><img src="<?php echo $i['logo'];?>" alt="<?php echo $i['title'];?>" /></a>
  	<?php endforeach;?>
  	
</div>
