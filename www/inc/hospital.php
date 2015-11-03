<div class="my_hospital" id="home_uboxstyle">

    <select class="myHSelect" onchange="alert('xx');">
            <?php $hospital=ext('hospital_list',100)?>
            <option value="">--查看其它连锁医院--</option>
            <?php foreach($hospital['list'] as $i):?>
            <option value="<?php echo $i['web']?>" >--<?php echo $i['name']?>--</option>
            <?php endforeach;?>       
    </select>
    
</div>   