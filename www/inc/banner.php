<div class="header">
    <div class="wp fluidgrid">
         <div class="row">
              <div class="col-5 mt-0">
                  <img alt="" src="images/logo.jpg"  />
              </div>
              <div class="col-3 mt-0">
                  <img alt="" src="images/head1.jpg"  />
              </div>
              <div class="col-4 text-r  mt-0">
                  <img alt="" src="images/phone.jpg"  />
              </div>
         </div>
    </div>
</div>
<div class="clear"></div>
<div class="navbg">
      <nav class="mainnav cl wp">
          <ul class="cl col-9">
                <li <?php if ($_SERVER[SCRIPT_URL]=='/'):?>class="current"<?php endif;?>><a href="/">首页</a></li>
                <li <?php if ($_SERVER[SCRIPT_URL]=='/index-esfang-showlist.html'):?>class="current"<?php endif;?>><a class="sj" href="/index-esfang-showlist.html">二手房</a>
                   <ul>
                       <li <?php if ($_SERVER[SCRIPT_URL]=='/index-esfang-showlist.html'):?>class="current"<?php endif;?>><a href="/index-esfang-showlist.html">出售房源</a></li>
                   </ul>
                </li>
                
                <li <?php if ($_SERVER[SCRIPT_URL]=='/index-rent-showlist.html'):?>class="current"<?php endif;?>><a class="sj" href="/index-rent-showlist.html">租房</a>
                <ul>
                    <li <?php if ($_SERVER[SCRIPT_URL]=='/index-rent-showlist.html'):?>class="current"<?php endif;?>><a href="/index-rent-showlist.html">出租住宅</a></li>
                    <li <?php if ($_SERVER[SCRIPT_URL]=='/index-bsrent-showlist.html'):?>class="current"<?php endif;?>><a href="/index-bsrent-showlist.html">出租别墅</a></li>
                </ul>
                </li>
                
				
				
                <li <?php if ($_SERVER[SCRIPT_URL]=='/index-xiezilou-showlist.html'):?>class="current"<?php endif;?>><a class="sj" href="/index-xiezilou-showlist.html">写字楼</a>
                  <ul>
                       <li <?php if ($_SERVER[SCRIPT_URL]=='/index-xiezilou-showlist.html'):?>class="current"<?php endif;?>><a href="/index-xiezilou-showlist.html">写字楼出售</a></li>
               		   <li <?php if ($_SERVER[SCRIPT_URL]=='/index-xzlrent-showlist.html'):?>class="current"<?php endif;?>><a href="/index-xzlrent-showlist.html">写字楼出租</a></li>
                  </ul>
                </li>
               
				
				
                <li <?php if ($_SERVER[SCRIPT_URL]=='/index-shangpu-showlist.html'):?>class="current"<?php endif;?>><a class="sj" href="/index-shangpu-showlist.html">商铺</a>
                    <ul>
                         <li <?php if ($_SERVER[SCRIPT_URL]=='/index-shangpu-showlist.html'):?>class="current"<?php endif;?>><a href="/index-shangpu-showlist.html">商铺出售</a></li>
                        <li <?php if ($_SERVER[SCRIPT_URL]=='/index-sprent-showlist.html'):?>class="current"<?php endif;?>><a href="/index-sprent-showlist.html">商铺出租</a></li>
                    </ul>
                </li>
               

                <li <?php if ($_SERVER[SCRIPT_URL]=='/index-article-showlist.html'):?>class="current"<?php endif;?>><a href="/index-article-showlist.html">房产快讯</a></li>
                <li><a href="#">手机找房</a></li>
          </ul>
          <div class="f-r ueserinfo">
				<?php if(session::get('membername')):?>
					 <a class="register" href="/index-member-logoff.html">退出</a>
					 <span ><?php echo session::get('membername');?>,你好!</span>
				<?php else:?> 
				   <a class="register" href="/index-member-register.html">注册</a>
				   <a class="login" href="/index-member-login.html">登录</a>
			   <?php endif?>
          </div>
     </nav>
</div>
<div class="searchbg">
    <div class="wp fluidgrid search">
         <div class="col-5 searchbox mt-10">
              <div class="searchBar">
                  <form class="form-search" method="post" action="http://www.zycfcw.com/index-esfang-showlist-page-0.html">
                    <input id="searchKeyword" name="kw_word" value="请输入搜索关键词" class="searchTxt" autocomplete="off" onfocus="if(this.value=='请输入搜索关键词')this.value=''" onblur="if(this.value=='')this.value='请输入搜索关键词'">
                    <input id="searchBtn" name="searchBtn" type="submit" name="submit" value="搜索" class="searchBtn" onclick="b_onclick()">
                  </form>
              </div>
         </div>
         <div class="col-7 mt-10 mb-10">
              <a>学区房</a>
              <a href="/index-esfangshuibiao.html">二手房税表</a>
              <a  href="/index-process.html">交房流程</a>
         </div>
    </div>
</div>
<div class="clear"></div>