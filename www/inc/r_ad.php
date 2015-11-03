<div class="r_a_d">
				<?php $adversite=ext('adversite','right_public')?>
                <?php if($adversite):?>	
				<a href="<?php echo $adversite['url']?>" target="_blank"><img width="310" height="161" src="<?php echo $adversite['logo']?>" alt="<?php echo $adversite['title']?>"/></a>
				<?php endif;?>
</div>