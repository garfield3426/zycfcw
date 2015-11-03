		<div class="other_article">
            	<div class="other_title">推荐文章</div>
                <div class="other_content">
                	<div class="other_list">          	
                		              
                    	<ul class="other_ul">
                    	<?php $recommend = ext('article_list',10,1,false,true); ?>
                        <?php foreach($recommend['list'] as $k=>$recommend): ?>   
                        	<li><a href="<?php echo $recommend['viewLink'];?>" title="<?php echo $recommend['title'];?>" target="_blank">-<?php echo $recommend['title'];?></a></li>	
                        <?php if(($k+1)%5==0&$k!=9) echo"</ul><ul class=\"other_ul\">"?>
                        <?php endforeach; ?>
                        </ul>
                        
                    </div>
                </div>
            </div>