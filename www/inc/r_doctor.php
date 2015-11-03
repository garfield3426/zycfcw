			<div class="r_doctor">
            	<div class="r_d_title"><div class="r_d_t">医生团队</div><a class="r_d_a" href="/index-doctor-showlist.html" target="_blank">更多&gt;&gt;</a></div>
                <div class="r_content">
					<?php $doctor = ext('doctor_list',2,76); ?>
					<?php foreach($doctor['list'] as $v):?>
                	<div class="r_d_one">
                    	<div class="d_img">
                        	<div class="doctor_img"><a href="<?php echo $v['viewLink']?>" target="_blank"><img src="<?php echo $v['img']?>" width="94" height="124" alt="<?php echo $v['name']?>"/></a></div>
                            <div class="r_d_name"><a href="<?php echo $v['viewLink']?>" target="_blank" title="<?php echo $v['name']?>"><?php echo $v['name']?></a></div>
                        </div>
                        <div class="d_intro">
                        	<div class="d_content">
                            	<div class="d_job"><?php echo $v['rank']?>&nbsp;&nbsp;&nbsp;<?php echo $v['duty']?></div>
                                <div class="d_more_txt"><?php echo $v['info']?>...<a href="<?php echo $v['viewLink']?>" target="_blank" title="<?php echo $v['name']?>">详细</a></div>
                            </div>
                            <div class="d_help">
                                <a class="refer" href="http://lvt.zoosnet.net/LR/Chatpre.aspx?id=LVT14303016"></a>
                                <a class="bespeak" href="/index-yuyue.html"></a>
                            </div>
                        </div>
                    </div>
					<?php endforeach;?>
                    
                </div>
            </div>