						<div class="clearfix mt-30 mb-30">
					            <span class="mt-20 f-l xy">每页显示：</span>
								<select class="form-control w80 mr-10 mt-20 f-l xy">
								  <option>20页</option>
								  <option>30页</option>
								  <option>40页</option>
								</select>
								<div class="fanye gray6 r">
					                <a id="PageControl1_hlk_first" href="/">首页</a> 
								<?php	
									if($v['pager']['prev']){
									echo "<a id=\"PageControl1_hlk_pre\" href=\"{$v['pager']['prev']}\" >上一页</a>";
								}else{
									echo "<a id=\"PageControl1_hlk_pre\" >上一页</a>";
								}
								foreach($v['pager']['list'] as $k=>$i){
									if($k == $v['pager']['current']){
										echo "<a class='pageNow'>{$k}</a>";
									}else{
										echo "<a  href=\"{$i}\">{$k}</a>";
									}
								}
								
								if($v['pager']['next']){
									echo "<a id=\"PageControl1_hlk_next\" href=\"{$v['pager']['next']}\" >下一页</a>";
								}else{
									echo "<a id=\"PageControl1_hlk_next\" >下一页</a>";
								}
									?>
									<span class="txt">共<?php echo $v['pager']['totalPage'];?>页</span>
					            </div>
							</div>