			<?php $links = ext('link_list',100); ?>
			<?php foreach($links['list'] as $k=>$links): ?>	
		         <a href="<?php echo $links['url']?>" title="<?php echo $links['title']?>" target="_blank"><?php echo $links['title']?></a>
			<?php endforeach; ?>	
				 