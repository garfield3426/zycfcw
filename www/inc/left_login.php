<div class="col-2 user-navmenu mt-15 mr-0">
		           <!--<div class="menu-group">
		                <h3 class="icon1">房源管理</h3>
		                <ul>
		                    <li class="on"><a href="#">管理出售房源</a></li>
		                    <li><a href="#">管理求购房源</a></li>
		                    <li><a href="#">管理出租房源</a></li>
		                    <li><a href="#">管理求租房源</a></li>
		                </ul>
	                </div> -->
	                <div class="menu-group">
		                <h3 class="icon2">账户设置</h3>
		                <ul>
		                    <li <?php if($_SERVER[SCRIPT_URL]=='/index-member-profile.html') echo 'class="on"';?>><a href="/index-member-profile.html">个人资料</a></li>
		                    <li <?php if($_SERVER[SCRIPT_URL]=='/index-member-safe.html') echo 'class="on"';?>><a href="/index-member-safe.html">安全设置</a></li>
		                    <li <?php if($_SERVER[SCRIPT_URL]=='/index-member-changepassword.html') echo 'class="on"';?>><a href="/index-member-changepassword.html">修改密码</a></li>
		                </ul>
	                </div>
	                <div class="menu-group">
		                <h3 class="icon3">二手房</h3>
		                <ul>
		                    <li <?php if($_SERVER[SCRIPT_URL]=='/index-esfang-create.html') echo 'class="on"';?>><a href="/index-esfang-create.html">发布出售房源</a></li>
		                    <li <?php if($_SERVER[SCRIPT_URL]=='/index-qiugou-create.html') echo 'class="on"';?>><a href="/index-qiugou-create.html">发布求购房源</a></li>
		                    <li <?php if($_SERVER[SCRIPT_URL]=='/index-fang-lists-ftype-1-fytype-1.html') echo 'class="on"';?>><a href="/index-fang-lists-ftype-1-fytype-1.html">管理出售二手房房源</a></li>
							
		                </ul>
	                </div>
					<div class="menu-group">
		                <h3 class="icon3">商铺/写字楼</h3>
		                <ul>
		                    <li <?php if($_SERVER[SCRIPT_URL]=='/index-sale-create.html') echo 'class="on"';?> ><a href="/index-sale-create.html">发布出售房源</a></li>
							<li <?php if($_SERVER[SCRIPT_URL]=='/index-fang-lists-ftype-1-fytype-245.html') echo 'class="on"';?>><a href="/index-fang-lists-ftype-1-fytype-245.html">管理出售写字楼房源</a></li>
							<li <?php if($_SERVER[SCRIPT_URL]=='/index-fang-lists-ftype-1-fytype-246.html') echo 'class="on"';?>><a href="/index-fang-lists-ftype-1-fytype-246.html">管理出售商铺房源</a></li>
							
		                    <li <?php if($_SERVER[SCRIPT_URL]=='/index-qiugou-create.html#') echo 'class="on"';?>><a href="/index-qiugou-create.html#">发布求购房源</a></li>
		                    <li <?php if($_SERVER[SCRIPT_URL]=='/index-qiugou-lists.html') echo 'class="on"';?>><a href="/index-qiugou-lists.html">管理求购房源信息</a></li>						
		                </ul>
	                </div>
					
					<div class="menu-group">
		                <h3 class="icon3">出租房源</h3>
		                <ul>
							 <li <?php if($_SERVER[SCRIPT_URL]=='/index-rent-create.html') echo 'class="on"';?>><a href="/index-rent-create.html">发布出租房源</a></li>
							 <li <?php if($_SERVER[SCRIPT_URL]=='/index-rent-lists.html') echo 'class="on"';?>><a href="/index-rent-lists.html">管理出租房源</a></li>
							 
							 <li <?php if($_SERVER[SCRIPT_URL]=='/index-qiuzu-create.html') echo 'class="on"';?>><a href="/index-qiuzu-create.html">发布求租房源</a></li>  
		                     <li <?php if($_SERVER[SCRIPT_URL]=='/index-qiuzu-lists.html') echo 'class="on"';?>><a href="/index-qiuzu-lists.html">管理求租房源</a></li>
		                </ul>
	                </div>
				</div>