			<div class="bespeak">
            	<div class="other_title">免费预约</div>
                <div class="b_content">
                	<div class="b_img"><a href="#"><img src="../image/bespeak_test.gif" /></a></div>
                    <form action="<?php echo Core::getUrl('simplesave','yuyue','','',true) ?>" method="post" class="my_bespeak">
                    	<div class="form_left">
                        	<div class="b_input"><label for="name" class="my_lable">您的姓名 : </label><input class="my_b_i" name="item[name]" type="text" /></div>
                            <div class="b_input"><label for="phone" class="my_lable">您的电话 : </label><input class="my_b_i" name="item[tel]" type="text" /></div>
                            <div class="b_textarea"><label for="txt" class="my_lable_t">您的病情 : </label><div class="my_txt"><textarea class="my_b_t" name="item[content]"></textarea></div></div>
                        </div>
                        <div class="form_left form_right">
                        	<div class="b_input"><label for="name" class="my_lable">到诊时间 : </label><input class="my_b_i" name="item[operation_time]" type="text" onfocus="WdatePicker({firstDayOfWeek:1})" /></div>
                            <div class="b_input">
                            	<label for="select" class="my_lable_s">就诊方向 :</label>
                                <div class="mySelect" id="uboxstyle">
                                	<select name="item[types]" class="mySelect" id="language_mac">
                                            <option value="">请选择就诊方向</option>
                                            <?php foreach($v['bespeak_type'] as $k=>$i):?>
	                                        <option value="<?php echo $k;?>"><?php echo $i;?></option>
	                                        <?php endforeach;?>
                                            
                                	</select> 
                                </div>
                            </div>
							<div class="b_input"><label for="phone" class="my_lable">验证号码 : </label><input class="number" name="item[code]" type="text" /><img src="index-default-code.html" align="absmiddle" id="verify_code"/></div>
                            <div class="bottom"><input type="submit" class="my_b_s" value="" /><input class="my_b_r" type="reset" value="" /></div>
                        </div>
                    </form>
                </div>
            </div>