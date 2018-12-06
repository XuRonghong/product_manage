<div id="header-div">
      <div id="logo-div" ><img src="images/webAdmin_logo.gif" alt="zw78 - power for zw78.com" width="195" height="50" /></div>
      <div id="submenu-div">
        <div class="txt1"> 陽光塑膠有限公司產品管理系統Beta &nbsp;&nbsp;&nbsp;&nbsp; <a href="logout.php" >系統登出</a> </div>
        
        
            <!--<div style=" float:right;  width:380px; height:45px; margin:2px 6px; overflow:hidden;"><a href="http://www.zhujiwu.com/priceall.asp?id=web" target="_blank"><img src="http://www.zw78.com/images/freead1.gif" border="0" /></a></div>-->
        
      </div>
</div>


<div id="body"><!--接著原始碼的一部份-->

    
<div id="left">



<div id="tabbar-div">
  <p><span style="float:right; padding: 3px 5px;" > 
  <img id="toggleImg" src="/webAdmin/sys_img/menu_minus.gif" width="9" height="9" border="0" alt="闭合" /> </span> <span class="tab-front" style="cursor:auto" id="menu-tab">網站管理菜單</span> </p>
</div>

<div id="main-div">
  <div id="top_menu"> 
  <!--<a href="tcpdf\examples\example_009.php" >网站预览&nbsp;</a> | 
  <a href="/webAdmin/main.asp" >管理首页&nbsp;</a> | -->
  <a href="edit.php?id=<?php echo $_SESSION['us'];?>&data=M" >修改密碼&nbsp;</a> | 
  <a href="#" >登入日誌&nbsp;</a> 
  </div>
  <div id="menu-list">
    <ul>
      <li class="explode" key="04_goods" name="menu"> 產品管理
        <ul>
		 <li class="menu-item"><a href="prdu_manage.php" >商品管理</a></li>
         <li class="menu-item"><a href="prdu_add.php" >添加新商品</a></li>
         <li class="menu-item"><a href="prdu_class.php" >商品類型管理</a></li>
        </ul>
      </li>      
      <li class="explode" key="01_goods" name="menu"> 網站常規設置 
        <ul>
          <li class="menu-item"><a href="member.php" >多管理員設置</a></li>
          <!--<li class="menu-item"><a href="Config_SEO.asp" >搜索引擎优化</a></li>
          <li class="menu-item"><a href="Config_TC.asp" >网站流量统计</a></li>-->
        </ul>
      </li>
        </ul>
      </li>
    </ul>
  </div>
</div>

<div style=" float:right;  width:190px; height:190px; margin:32px 16px; overflow:hidden;"><!--<a href="http://www.zhujiwu.com/priceall.asp?id=web" target="_blank"><img src="http://www.zw78.com/images/freead1.gif" border="0" /></a>--></div>


</div>