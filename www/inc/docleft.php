<div class="map">
		<img src="images/mapExperts_1.jpg" width="980" height="48" /><img src="images/mapExperts_2.jpg" width="980" height="45" /><img src="images/mapExperts_3.jpg" width="980" height="40" /><img src="images/mapExperts_4.jpg" width="980" height="62" /><img src="images/mapExperts_5.jpg" width="980" height="49" /><img src="images/mapExperts_6.jpg" width="980" height="55" /><img src="images/mapExperts_7.jpg" width="980" height="26" />
    </div>
    <!------mainContent------>
    <div class="main">
	    <!------LeftLink------>
		<div class="left">
			<div  class="lTop"></div>
			<div class="<?php if($_SERVER[SCRIPT_URL]=="/index-doctor-showlist.html") echo "lLink2"; else echo"lLink";?>">
				<a href="index-doctor-showlist.html" target="_parent"><span>专家列表页</span></a>
			</div>
			<?php foreach($v[branch] as $value):?>
			<div class="<?php if($_SERVER[SCRIPT_URL]=="/index-doctor-showlist-b_id-$value[id].html") echo "lLink2"; else echo"lLink";?>">
				<a href="index-doctor-showlist-b_id-<?php echo "$value[id]";?>.html" target="_parent"><span><?php echo "$value[name]"?></span></a>
			</div>
			<?php endforeach;?>
			<div class="lBottom1"></div>
			<div class="lfooter"></div>
		</div>
		<!------End of LeftLink------>