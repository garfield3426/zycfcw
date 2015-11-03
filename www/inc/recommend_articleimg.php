		<div class="subject">
            	<div class="other_title">推荐文章</div>
                <div class="other_content">
                	<div class="subject_ul">
                		<?php $recommend = ext('article_list',4,1,true); ?>
                        <?php foreach($recommend['list'] as $k=>$recommend): ?>   
                    	<div class="subject_li">
                        	<div class="subject_img"><a href="<?php echo $recommend['viewLink'];?>" title="<?php echo $recommend['title'];?>" target="_blank"><img src="<?php echo $recommend['img'];?>" /></a></div>
                            <div class="s_title"><a href="<?php echo $recommend['viewLink'];?>" title="<?php echo $recommend['title'];?>" target="_blank"><?php echo $recommend['title'];?></a></div>
                        </div>
                        <?php endforeach; ?>
                        
                    </div>
                </div>
            </div>