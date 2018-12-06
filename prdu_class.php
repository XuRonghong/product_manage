<?php require_once('Connections/prdu_manage.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

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
    if (($strUsers == "") && true) { 
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

mysql_select_db($database_prdu_manage, $prdu_manage);
$query_show_class = "SELECT * FROM `class` WHERE cPre=0 ORDER BY cId ASC ";
$show_class = mysql_query($query_show_class, $prdu_manage) or die(mysql_error());
//$row_show_class = mysql_fetch_assoc($show_class);
$totalRows_show_class = mysql_num_rows($show_class);

	if($totalRows_show_class==0){
		$insertSQL = sprintf("INSERT INTO `class` (cId,cName,cPre) VALUES (%s,%s,%s) ",
						GetSQLValueString("1", "int"),
                       GetSQLValueString("無分類", "text"),
					   GetSQLValueString("0", "int"));
		  mysql_select_db($database_prdu_manage, $prdu_manage);
		  $Result1 = mysql_query($insertSQL, $prdu_manage) or die(mysql_error());	
	}





//header("Location: login.php");
//echo "login success";
?>
<!doctype html>
<html><head>
<meta charset="utf-8">
<title>商品類型管理</title>

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
<style type="text/css">
#LR-table a{text-decoration:none}
#LR-table a:hover{text-decoration:none}
#float-tab a{color:#FFF;}
</style>


</head>
<body>

    <?php require("_top.php"); ?>
    
    <!-- main coding-->
    <div id="right">
	
    <h1>網店管理  >> 商品分類管理 </h1>
<div class="tab-div">
  <div id=tabbar-div>
    <P> <span class="tab-front" id="LR-tab">商品分類管理</span> <span class="tab-back" id="float-tab"><a href="prdu_class_add.php?cpre=0">添加父分類</a></span></P>
  </div>
  <div class="list-div">
  <table width="90%" align="center"  id="LR-table" >
<tbody>
              <tr>
                <th width="57%" align="left" valign="middle">&nbsp;類别名稱</th>
                <th width="7%" align="center" valign="middle">&nbsp;</th>
                <th width="36%" align="left" valign="middle">&nbsp;管理【<a href="" >修改及刪除</a>】</th>
              </tr>
              <tr>
                <td colspan="3" height="8"></td>
              </tr>
              
            <tr >
                <td align="left" valign="middle">公司產品&nbsp;[<span style='color:#FF0000'><?php echo $totalRows_show_class;?></span>]</td>
                <td align="center" valign="middle">
                </td>
                <td align="left" valign="middle"><!--<a href='?action=addClass&ParentID=1' title='添加子栏目'><img src='sys_img/add.gif' width='16' height='16' border='0' /></a>
                  &nbsp;&nbsp;&nbsp;&nbsp;<a href="?action=editClass&CataID=1" title="修改"><img src="sys_img/edit.gif" width="16" height="16" border="0" /></a>-->&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
</tr>
              
              <?php while ($row_show_class = mysql_fetch_assoc($show_class)){ ?>
              
<?php
$query_show_class2 = "SELECT * FROM `class` WHERE cPre='".$row_show_class['cId']."'";
$show_class2 = mysql_query($query_show_class2, $prdu_manage) or die(mysql_error());
$totalRows_show_class2 = mysql_num_rows($show_class2);
?>    
                <!--<tr onmouseover="this.style.background='#f4fafb'" onmouseout="this.style.background='#ffffff'">-->
                <tr >
                  <td align="left" valign="middle">
                  &nbsp;&nbsp;└&nbsp;<?php echo $row_show_class['cName'];?>&nbsp;[<span style='color:#FF0000'><?php echo $totalRows_show_class2;?></span>]</td>
                 <td align="center" valign="middle">
                   <!--<a href='Products_Cata.asp?action=downOne&CataID=2' title='降低' target='temp_frame' onclick='return showLayer();'><img src='images/down.gif' border='0' style='margin:2px' /></a>-->                </td>
                  <td align="left" valign="middle"><a href='prdu_class_add.php?cpre=<?php echo $row_show_class['cId'];?>' title='添加子欄目'><img src='images/add.gif' width='16' height='16' border='0' /></a>
                    &nbsp;&nbsp;&nbsp;&nbsp;<a href="edit.php?id=<?php echo $row_show_class['cId'];?>&data=C&cpre=<?php echo $row_show_class['cPre'];?>" title="修改"><img src="images/edit.gif" width="16" height="16" border="0" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if($row_show_class['cName']!="無分類"){ ?>
                    <a href="del.php?mId=<?php echo '';?>&id=<?php echo $row_show_class['cId'];?>&data=C" onclick="return confirm('确定要刪除吗?');"><img src='images/del.gif' width='16' height='16' border='0' /></a> <?php } ?></td>
              </tr>



<?php

while($row_show_class2 = mysql_fetch_assoc($show_class2)){
?>

<tr >
                  <td align="left" valign="middle">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row_show_class2['cName'];?>&nbsp;</td>
                 <td align="center" valign="middle">
                   <!--<a href='Products_Cata.asp?action=downOne&CataID=2' title='降低' target='temp_frame' onclick='return showLayer();'><img src='images/down.gif' border='0' style='margin:2px' /></a>-->                </td>
                  <td align="left" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;<!--<a href='?action=addClass&ParentID=2' title='添加子栏目'><img src='images/add.gif' width='16' height='16' border='0' /></a>-->
                    &nbsp;&nbsp;&nbsp;&nbsp;<a href="edit.php?id=<?php echo $row_show_class2['cId'];?>&data=C&cpre=<?php echo $row_show_class2['cPre'];?>" title="修改"><img src="images/edit.gif" width="16" height="16" border="0" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if($row_show_class2['cName']!="無分類"){ ?>
                    <a href="del.php?mId=<?php echo '';?>&id=<?php echo $row_show_class2['cId'];?>&data=C" onclick="return confirm('确定要刪除吗?');"><img src='images/del.gif' width='16' height='16' border='0' /></a> <?php } ?></td>
              </tr>   
                     
<?php
	}
?>

                <?php }  ?>
              
              
              
              <tr>
                <td colspan="3" height="8"></td>
            </tr>
   <tr>
      <td colspan="3" align="center" valign="middle">
	          </td>
   </tr>
   </tbody>
  </table>
  
  </div>
</div>
<div class="list-div" style="margin-top:20px">
  <table width="90%" align="center"  id="LR-table" >
    <tbody>
      <tr>
        <th>商品分类管理说明</th>
      </tr>
      <tr>
        <td class="footer"><ol>
            <li>在这里您可以建立商品分类（支持二级分类），只有先添加好分类后才可以添加商品。</li>
            </ol></td>
      </tr>
    </tbody>
  </table>
  <table width="90%" align="center"  id="float-table" style="display:none">
    <tbody>
      <tr>
        <th >添加一级栏目说明</th>
      </tr>
      <tr>
        <td class="footer"><ol>
            <li>如果您启用了主页转发功能,那么你的域名将会转发到您所填写的“主页转发地址”。</li>
            <li>通过填写&quot;主页转发地址&quot;,结合本站的“网络硬盘”系统，,您可以完全独立的来设计网页,详细功能请参考本站的帮助信息。</li>
            <li>注意！如果您启用了“主页转发”，您选择的本站模板将会不起作用，一般情况下您是不需要启用主页转发功能的。 </li>
          </ol></td>
      </tr>
    </tbody>
  </table>
</div>
<script src="../js/Utils.js" type=text/javascript></script>
<script src="../js/Tab.js" type=text/javascript></script>
<iframe style="display:none" name="temp_frame"></iframe>

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
mysql_free_result($show_class);
?>
