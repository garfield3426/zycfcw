			<div class="ask_list">
            	<div class="r_d_title"><div class="r_d_t">问答列表</div></div>
                <div class="ask_content">
                    <ul class="ask_ul">
                    	<?php $client = ext('client',5); ?>
						<?php foreach($client['list'] as $v):?>
                        <li><a href="<?php echo $v['viewLink']?>" target="_parent" title="<?php echo $v['title']?>"><?php echo $v['title']?></li>
                        <?php endforeach;?>
                        
                    </ul>
                </div>    
            </div>