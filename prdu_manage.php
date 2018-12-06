<?php require_once('Connections/prdu_manage.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1,2";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "logout.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_show_product = 10;
$pageNum_show_product = 0;
if (isset($_GET['pageNum_show_product'])) {
  $pageNum_show_product = $_GET['pageNum_show_product'];
}
$startRow_show_product = $pageNum_show_product * $maxRows_show_product;



mysql_select_db($database_prdu_manage, $prdu_manage);
$query_show_product = "SELECT * FROM product JOIN class ON product.cId=class.cId ORDER BY pId DESC ";
$query_limit_show_product = sprintf("%s LIMIT %d, %d", $query_show_product, $startRow_show_product, $maxRows_show_product);
$show_product = mysql_query($query_limit_show_product, $prdu_manage) or die(mysql_error());
$row_show_product = mysql_fetch_assoc($show_product);

if (isset($_GET['totalRows_show_product'])) {
  $totalRows_show_product = $_GET['totalRows_show_product'];
} else {
  $all_show_product = mysql_query($query_show_product);
  $totalRows_show_product = mysql_num_rows($all_show_product);
}
$totalPages_show_product = ceil($totalRows_show_product/$maxRows_show_product)-1;



//搜尋欄的功能，根據分類找商品
mysql_select_db($database_prdu_manage, $prdu_manage);
$query_show_class = "SELECT * FROM `class` WHERE cPre=0 ORDER BY cId ASC ";
$show_class = mysql_query($query_show_class, $prdu_manage) or die(mysql_error());
$row_show_class = mysql_fetch_assoc($show_class);
$totalRows_show_class = mysql_num_rows($show_class);

$queryString_show_product = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_show_product") == false && 
        stristr($param, "totalRows_show_product") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_show_product = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_show_product = sprintf("&totalRows_show_product=%d%s", $totalRows_show_product, $queryString_show_product);

//header("Location: login.php");
//echo "login success";
?>
<!doctype html>
<html><head>
<meta charset="utf-8">
<title>陽光塑膠有限公司產品管理系統Beta</title>

<?php require("_include.php"); ?>

<script type="text/javascript" >
$().ready(function(e) {
    /*$("#right").load("prdu_list.php");
	$("#left").load("left_menu.php");
	
	$("prdu_list.php #p_add").click(function(e) {
        $("#right").load("prdu_add.php");
		alert('gg');
    });
	*/
});
</script>
<link href="css/prdu_list.css" rel="stylesheet"></link>

</head>
<body>

    <?php require("_top.php"); ?>
    
    <!-- main coding-->
    <div id="right">
    
        
<h1><span class="action-span"><a href="prdu_add.php" >添加新商品</a></span>網站管理 >> 商品管理</h1>
<div class="list-div">
<H1 style="margin:3px 1px 3px 1px;" id="showSearch">商品列表
  <form style="border-top:1px solid #BBDDE5; margin-top:10px;" method="get" action="prdu_search.php" >
    <img height="22" src="sys_img/icon_search.gif"  /> 商品分類:
   
      <select name="CataID" onchange="javascript:this.form.submit();">
        <option value="-1">全部</option>
         <?php do { ?>
        <option value='<?php echo $row_show_class['cId'];?>'>&nbsp;&nbsp;└&nbsp;<?php echo $row_show_class['cName'];?></option>        
      <?php } while ($row_show_class = mysql_fetch_assoc($show_class)); ?>
      </select>
&nbsp;
	商品名稱：<input type="text" name="ProductName" />
    <input type="submit" class="button" value="搜索" />
  </form>
</H1>
  <table width="90%" align="center"  id="LR-table">
  </table>
  <table width="100%" align="center"  id="LR-table">
        <tbody>
        <form name="shopManger" action="Products.asp?action=ManySet" method="post" target="temp_frame" onsubmit="return showLayer()">
          <tr>
            <th width="4%" >序</th>
            <th width="19%" >商品名稱</th>
            <th width="13%" >商品編號</th>
            <th width="10%" >單價</th>
            <th width="12%" >商品分類</th>
            <th width="14%" >尺寸</th>
            <!--<th width="11%" >紙箱尺寸</th>-->
            <th width="7%" >裝箱數量</th>
            <th width="7%" >裝箱重量</th>
            <th width="14%" >操作</th>
          </tr>
          <tr><td colspan="11" height="8" ></td></tr>
          
          <?php if ($totalRows_show_product > 0) { // Show if recordset not empty ?>
  <?php $i=1; do { ?>
    <tr>
      <td align="center" valign="middle"><?php echo $i++; ?><!--<input type="checkbox" name="checkbox" id="checkbox" value="30" />--></td>
      <td align="center" valign="middle" style="text-align:center"><a href="Product_ok.php?id=<?php echo $row_show_product['pId']; ?>" ><?php echo $row_show_product['pName']; ?></a></td>
      <td align="center" valign="middle"><?php echo $row_show_product['pNo']; ?></td>
      <td align="center" valign="middle"><?php echo $row_show_product['pPrice']; ?></td>
      <td align="center" valign="middle"><?php echo $row_show_product['cName']; ?></td>
      <td align="center" valign="middle"><?php echo $row_show_product['pSize']; ?></td>
      <!--<td align="center" valign="middle"><?php //echo $row_show_product['pCartonsize']; ?></td>-->
      <td align="center" valign="middle"><?php echo $row_show_product['pPcs']; ?></td>
      <td align="center" valign="middle"><?php echo $row_show_product['pKg']; ?></td>
      <td width="14%" align="center" valign="middle"  ><a href="pdfoutput.php?id=<?php echo $row_show_product['pId'];?>" target="_brank"><img src="images/pdf.jpg" width="16" height="16" border="0" alt="PDF" title="PDF產品手冊"/></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="edit.php?id=<?php echo $row_show_product['pId'];?>&data=P"><img src="images/edit.gif" width="16" height="16" border="0" alt="编辑"  title="编辑"/></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="del.php?mId=<?php echo '';?>&id=<?php echo $row_show_product['pId'];?>&data=P" onclick="return confirm('确定要刪除吗?');"><img src="images/del.gif" width="16" height="16" border="0" alt="刪除" title="刪除"/></a></td>
    </tr>
    <?php } while ($row_show_product = mysql_fetch_assoc($show_product)); ?>
            <?php } // Show if recordset not empty ?>
            
            
<tr><td colspan="11" height="8" ></td></tr>
          <tr>
            <td colspan="11" align="right" >&nbsp;<!--<input name="checkall" type="checkbox" id="checkall" onclick="CheckAll(shopManger,checkall)" value="checkall"/>
选中所有&nbsp;
<select name="operate" id="operate" onchange="return showMoveToCate();">
  <option value="" selected >选择操作</option>
  <option value="OnShelf">设为上架</option>
  <option value="UnderShelf">设为下架</option>
  <option value="SetFine">设为精品</option>
  <option value="UnSetFine">取消精品</option>
  <option value="SetNew">设为新品</option>
  <option value="UnSetNew">取消新品</option>
  <option value="SetHot">设为热销</option>
  <option value="UnSetHot">取消热销</option>
  <option value="MovedToRecycle">放入回收站</option>-->
  <!--<option value="DelMerchandise">删除所选商品</option>
  <option value="MoveToOtherCate">移到其他分类</option>
</select>
<!--<select name="moveToCate" id="moveToCate" style="display:none;" >
  <option value='1'>公司产品</option><option value='2'>&nbsp;&nbsp;├&nbsp;产品第一分类</option><option value='3'>&nbsp;&nbsp;└&nbsp;产品第二分类</option>
</select>
<input class="button" name="submit" type="submit" value="操作选定" onclick="return operateprompt(operate);" />&nbsp;--></td>
          </tr>
          </form>
          <tr>
            <td colspan="11" align="center" >
            <div style='text-align:center;'>
            <form name='showpages' method='Post' action='Products.asp'>
            共有 <b><?php echo $totalRows_show_product ?> </b> 件商品&nbsp;&nbsp;
            
            <a href="<?php printf("%s?pageNum_show_product=%d%s", $currentPage, 0, $queryString_show_product); ?>">首頁</a> 
            <a href="<?php printf("%s?pageNum_show_product=%d%s", $currentPage, max(0, $pageNum_show_product - 1), $queryString_show_product); ?>">上一頁</a>&nbsp;
            <a href='<?php printf("%s?pageNum_show_product=%d%s", $currentPage, min($totalPages_show_product, $pageNum_show_product + 1), $queryString_show_product); ?>'>下一頁</a>&nbsp;
            <a href='<?php printf("%s?pageNum_show_product=%d%s", $currentPage, $totalPages_show_product, $queryString_show_product); ?>'>尾頁</a>&nbsp;頁次：
            
            <strong>
            <font color=red>
			<?php echo $pageNum_show_product+1; ?>
            </font>/
			<?php echo ceil($totalRows_show_product/$maxRows_show_product ); ?>
            </strong>頁 &nbsp;<b>10</b>件商品/頁&nbsp;
            <!--转到：<select name='page' size='1' onchange='javascript:this.form.submit();'><option class='pageSelect' value='1' selected >第1页</option><option class='pageSelect' value='2'>第2页</option><option class='pageSelect' value='3'>第3页</option></select>--></form></div></td>
          </tr>
        </tbody>

      </table>
</div>
<div class="list-div" style="margin-top:20px">
  <table width="90%" align="center"  id="pop-table">
    <tbody>
      <tr>
        <th >商品列表使用说明</th>
      </tr>
      <tr>
        <td class="footer"><ol>
            <li>这里是对您商品进行管理的地方，在这里您可以添加/删除/產品電子書PDF等操作。</li>
            <li>只要您稍微研究下此页面还是不难熟悉的！</li>
            </ol></td>
      </tr>
    </tbody>
  </table>
</div>
<iframe style="display:none;" name="temp_frame"></iframe>

<!--服务器端操作时背景失效对话框-->

<style type="text/css">
<!--
#shadow_layer { display:none;  position:absolute; top:0px; left:0px;  filter:alpha(opacity=20); opacity:0.2; background-color: #000000; z-Index:1000; } 
#runing_layer { position:absolute; top:0px; left:0px; width:200px; height:100px; display:none; z-Index:1001; border:2px solid black; background-color:#FFFFFF; text-align:center; vertical-align:middle;padding:10px;} 
-->
</style>


<div id="shadow_layer"> </div>
<div id="runing_layer" style="display:none;"> <span id="runing_text" style="color:#000000"></span><br />
  <input id="runing_redirect_url" type="hidden" />
  <input id="runing_close" type="button" onclick="hideLayer();" value="关闭" />
  <input id="runing_refresh" style="display:none"  type="button" onclick="window.location.reload()" value="关闭"  />
  <input id="runing_redirect" style="display:none"  type="button" onclick="window.location=runing_redirect_url.value" value="关闭"  />
<iframe style="width:100%; height:1px;filter:alpha(opacity=0);-moz-opacity:0;z-Index:1; margin:0; padding:0" frameborder="0"></iframe>
</div>


<script language="JavaScript" type="text/javascript">
			var form_submit_ok=false; //定义全局变量，判断表单是否已经提交过
			
            function getBrowserHeight() { 
                var intH = 0; 
                var intW = 0; 
              
			  
			  document.documentElement.clientHeight>document.documentElement.scrollHeight ? intH = document.documentElement.clientHeight : intH = document.documentElement.scrollHeight;
			  document.documentElement.clientWidth>document.documentElement.scrollWidth ? intW = document.documentElement.clientWidth : intW = document.documentElement.scrollWidth;
			  

                return { width: parseInt(intW), height: parseInt(intH) }; 
            }  

            function setLayerPosition() { 
                var shadow = document.getElementById("shadow_layer"); 
                var question = document.getElementById("runing_layer"); 

                var bws = getBrowserHeight(); 
                shadow.style.width = bws.width + 'px'; 
                shadow.style.height = bws.height + 'px'; 
				
                question.style.left = parseInt((bws.width - 200) / 2)+'px'; 
				question.style.top=screen.availHeight/2+document.documentElement.scrollTop-100+'px';
				if (bws.height<screen.availHeight) question.style.top=parseInt(bws.height-100)/2+'px';
                shadow = null; 
                question = null; 
            } 

            function showLayer() { 
			   if (form_submit_ok=="true")  return false;
			   
			   
                setLayerPosition(); 

                var shadow = document.getElementById("shadow_layer"); 
                var question = document.getElementById("runing_layer"); 
				

				
				document.getElementById("runing_text").innerHTML="正在处理您的请求......"; 
				
				
				var ie=!-[1,];
				if(!ie){
					var sTop = document.body.scrollTop ? document.body.scrollTop : document.documentElement.scrollTop;
					question.style.top=parseInt(question.style.top)+sTop+"px";
				}
				shadow.style.display = 'block'; 
				question.style.display = 'block'; 

                shadow = null; 
                question = null;  
				form_submit_ok="true" ;
            } 
            
            function hideLayer() { 
                var shadow = document.getElementById("shadow_layer"); 
                var question = document.getElementById("runing_layer"); 

                shadow.style.display = 'none'; 
                question.style.display = 'none'; 

                shadow = null; 
                question = null;
				form_submit_ok="false" ;
            } 

           // window.onresize = setLayerPosition; 
		   
		   
		    function pppppp(){return false}
</script>

    
    </div><!-- main coding end-->
    
    <?php require("_bottom.php"); ?>
</div>
</body>
</html>
<?php
mysql_free_result($show_product);

mysql_free_result($show_class);
?>
