<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "2";
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

$MM_restrictGoTo = "NOthing.php";
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
<?php require_once('Connections/prdu_manage.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
/*if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addMember")) {	
  $insertSQL = sprintf("INSERT INTO member (mUser, mPassword, mLayer) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['mUser'], "text"),
                       GetSQLValueString($_POST['mPassword'], "text"),
                       GetSQLValueString($_POST['mClass'], "int"));

  mysql_select_db($database_prdu_manage, $prdu_manage);
  $Result1 = mysql_query($insertSQL, $prdu_manage) or die(mysql_error());
  
  header(sprintf("Location: %s", $editFormAction));
}

$maxRows_showMember = 5;
$pageNum_showMember = 0;
if (isset($_GET['pageNum_showMember'])) {
  $pageNum_showMember = $_GET['pageNum_showMember'];
}
$startRow_showMember = $pageNum_showMember * $maxRows_showMember;

$q="";
if (isset($_GET['q'])) {
  $q = $_GET['q'];
}
mysql_select_db($database_prdu_manage, $prdu_manage);
$query_showMember = "SELECT * FROM member WHERE mUser LIKE '%".$q."%' ORDER BY mId ASC " ;
$query_limit_showMember = sprintf("%s LIMIT %d, %d", $query_showMember, $startRow_showMember, $maxRows_showMember);
$showMember = mysql_query($query_limit_showMember, $prdu_manage) or die(mysql_error());
$row_showMember = mysql_fetch_assoc($showMember);

if (isset($_GET['totalRows_showMember'])) {
  $totalRows_showMember = $_GET['totalRows_showMember'];
} else {
  $all_showMember = mysql_query($query_showMember);
  $totalRows_showMember = mysql_num_rows($all_showMember);
}
$totalPages_showMember = ceil($totalRows_showMember/$maxRows_showMember)-1;

$queryString_showMember = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_showMember") == false && 
        stristr($param, "totalRows_showMember") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_showMember = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_showMember = sprintf("&totalRows_showMember=%d%s", $totalRows_showMember, $queryString_showMember);


//header("Location: login.php");
//echo "login success";

/*if ((isset($_POST["MM_group"])) && ($_POST["MM_group"] == "checkbox") &&(isset($_POST["ckbox"])) ) {
	
	$ckarray = $_post['ckbox'];
	
	
	
	foreach($_post['ckbox'] as $i => $value){
	echo("gg".$value);}
		$deleteSQL2 = sprintf("DELETE FROM member WHERE mId=%s",
							   GetSQLValueString($value, "int"));
		
		  mysql_select_db($database_prdu_manage, $prdu_manage);
		  $Result2 = mysql_query($deleteSQL2, $prdu_manage) or die(mysql_error());
		
	}
	
  header(sprintf("Location: %s", $editFormAction));
}*/


?>
<!doctype html>
<html><head>
<meta charset="utf-8">
<title>多管理員設置</title>

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

function us_ck(us){
	$.ajax({
						url: "us_ck.php",
						type: 'get',
						dataType: "html",
						data: "mUser="+us,
						success: function(data){							
							 $('#us_ck').text(data);
						},
						error: function(data) { 
							alert('Ajax request 發生錯誤');
						},
    					complete: function(data) { 
						}
					});
	//return true;
}

function validate2()
   {
     /*var sname;
	 sname=document.getElementById("mUser").value;
     if(trim(sname)=="")
	   { alert("管理員帳號不能为空"); return false; }
	 if(document.theForm.cat_id.value==0)
	   { alert("请选择商品分类"); return false; } 
		return true;*/

		us_ck(document.addMember.mUser.value);
		
		if(document.addMember.mUser.value=="")
		{
			alert("请输入用户名！");
			document.addMember.mUser.focus();
			return false;
		}
		else if(/[^a-zA-Z0-9\-]/g.test(document.addMember.mUser.value) || /[^a-zA-Z]/g.test(document.addMember.mUser.value.substr(0,1)))
		{
			alert("用户名只能用数字或英文,字母开头!");
			document.addMember.mUser.focus();
			return false;
		}
		else if(document.addMember.mPassword.value=="")
		{
			alert("请输入密码！");
			document.addMember.mPassword.focus();
			return false;
		}
		else if(/[^a-zA-Z0-9\-]/g.test(document.addMember.mPassword.value) || /[^a-zA-Z]/g.test(document.addMember.mPassword.value.substr(0,1)))
		{
			alert("密码只能用数字或英文,字母开头!");
			document.addMember.mPassword.focus();
			return false;
		}
		
		else if(document.addMember.mPassword.value!=document.addMember.mPassword_check.value)
		{
			alert("密码重複不一樣！");
			document.addMember.mPassword_check.focus();
			return false;
		}
		
		else if( $('#us_ck').text()!="" ){
			return false;
			//$('#addMember').attr('action',"");
			//document.addMember.onsubmit;
			//return showLayer();
		}
		return true;
	}
	
	
function check()
{
	var all=document.getElementsByName("checkbox");
	for(var i=0;i<all.length;i++)
	{
		if(document.getElementById("allchecked").checked==true)
		{
			all[i].checked=true;
		}
		else
		 {   all[i].checked=false;}
	}
}

function   formsubmit()   
{   
	  shopManger.action   =   ""   
	  shopManger.submit();   
}   
</script>
<link href="css/prdu_list.css" rel="stylesheet"></link>

</head>
<body>

    <?php require("_top.php"); ?>
    
    <!-- main coding-->
    <div id="right">
    
        
<!--<h1><span class="action-span"><a href="prdu_add.php" >添加新商品</a></span>网站管理 >> 商品管理</h1>-->
<div class="list-div">
<H1 style="margin:3px 1px 3px 1px;" id="showSearch">管理員帳號列表
  <form style="border-top:1px solid #BBDDE5; margin-top:10px;" method="get" action="" >
    <img height="22" src="sys_img/icon_search.gif"  />   
	&nbsp;
	管理員帳號：<input type="text" name="q" value="<?php echo $_GET['q'];?>"/>
    <input type="submit" class="button" value="搜索" />
  </form>
</H1>
  <table width="90%" align="center"  id="LR-table">
  </table>
  <table width="100%" align="center"  id="LR-table">
        <tbody>
        <form name="shopManger" id="shopManger" action="" method="post" onsubmit="return showLayer()">
          <tr>
            <!--<th width="10%" >全选<input id="allchecked" type="checkbox" onclick="check();"></th>-->
            <th width="24%" >管理員帳號</th>
            <th width="13%" >管理層級</th>
            <th width="18%" >管理密碼</th>
            <th width="14%" >加入日期</th>
            <th width="5%" ></th>
            <th width="3%" ></th>
            <th width="13%" >操作</th>
          </tr>
          <tr><td colspan="11" height="8" ></td></tr>
          
          
          <?php if ($totalRows_showMember > 0) { // Show if recordset not empty ?>
  <?php do { ?>
    <tr>
      <!--<td align="center" valign="middle"><input type="checkbox" name="ckbox[]" id="checkbox" value="<?php //echo $row_showMember['mId'];?>" /></td>-->
      <td align="center" valign="middle" style="text-align:center"><?php echo $row_showMember['mUser'];?></td>
      <td align="center" valign="middle"><?php echo ($row_showMember['mLayer']==1)?"管理員":"超級管理員";?></td>
      <td align="center" valign="middle"><input type="password" disabled="true" value="
	  <?php echo $row_showMember['mPassword'];?>" size="10">
      </td>
      <td align="center" valign="middle"><?php echo $row_showMember['mTime'];?></td>
      <td align="center" valign="middle"></td>
      <td align="center" valign="middle"></td>
      <td width="10%" align="center" valign="middle"  ><a href="edit.php?id=<?php echo $row_showMember['mId'];?>&data=M"><img src="images/edit.gif" width="16" height="16" border="0" alt="编辑" /></a><a href="del.php?mId=<?php echo '';?>&id=<?php echo $row_showMember['mId'];?>&data=M" onclick="return confirm('确定要刪除吗?');"><img src="images/del.gif" width="16" height="16" border="0" alt="刪除" /></a></td>
    </tr>
    <?php } while ($row_showMember = mysql_fetch_assoc($showMember)); ?>
            <?php } // Show if recordset not empty ?>
            
            
<tr><td colspan="11" height="8" ></td></tr>
          <tr>
            <td colspan="11" align="right" >
<!--被选中&nbsp;
<a href="" onclick="if( confirm('确定要刪除所選的吗?') ){this.form.submit();}else{   return false;}"><img src="images/del.gif" width="16" height="16" border="0" alt="刪除" /></a>-->

<!--<select name="operate" id="operate" onchange="return showMoveToCate();">
  <option value="" selected >选择操作</option>
  <option value="OnShelf">设为上架</option>
  <option value="UnderShelf">设为下架</option>
  <option value="SetFine">设为精品</option>
  <option value="UnSetFine">取消精品</option>
  <option value="SetNew">设为新品</option>
  <option value="UnSetNew">取消新品</option>
  <option value="SetHot">设为热销</option>
  <option value="UnSetHot">取消热销</option>
  <option value="MovedToRecycle">放入回收站</option>
  <option value="DelMerchandise">删除所选商品</option>-->
  <!--<option value="MoveToOtherCate">移到其他分类</option>
</select>
<select name="moveToCate" id="moveToCate" style="display:none;" >
  <option value='1'>公司产品</option><option value='2'>&nbsp;&nbsp;├&nbsp;产品第一分类</option><option value='3'>&nbsp;&nbsp;└&nbsp;产品第二分类</option>
</select>
<input class="button" name="submit" type="submit" value="操作选定" onclick="return operateprompt(operate);" />&nbsp;--></td>
          </tr>
          </form>
          <tr>
            <td colspan="11" align="center" ><div style='text-align:center;'><form name='showpages' method='Post' action=''>共有 <b><?php echo $totalRows_showMember ?></b> 位人員&nbsp;&nbsp;<a href="<?php printf("%s?pageNum_showMember=%d%s", $currentPage, 0, $queryString_showMember); ?>">首頁</a> <a href="<?php printf("%s?pageNum_showMember=%d%s", $currentPage, max(0, $pageNum_showMember - 1), $queryString_showMember); ?>">上一頁</a>&nbsp;<a href='<?php printf("%s?pageNum_showMember=%d%s", $currentPage, min($totalPages_showMember, $pageNum_showMember + 1), $queryString_showMember); ?>'>下一頁</a>&nbsp;<a href='<?php printf("%s?pageNum_showMember=%d%s", $currentPage, $totalPages_showMember, $queryString_showMember); ?>'>尾頁</a>&nbsp;頁次：<strong><font color=red><?php echo ($startRow_showMember/$maxRows_showMember + 1) ?></font>/<?php echo ceil($totalRows_showMember/$maxRows_showMember ) ?></strong>頁 &nbsp;<b>5</b>位/頁
            <!--&nbsp;转到：<select name='page' size='1' onchange='javascript:this.form.submit();'><option class='pageSelect' value='1' selected >第1页</option><option class='pageSelect' value='2'>第2页</option><option class='pageSelect' value='3'>第3页</option></select>-->
            </form></div></td>
          </tr>
        </tbody>

      </table>
</div>
<div class="list-div" style="margin-top:20px">
  <table width="90%" align="center"  id="pop-table">
    <tbody>
      <tr>
        <th >管理員列表使用说明</th>
      </tr>
      <tr>
        <td class="footer"><ol>
            <li>这里是对您多管理員进行管理的地方，在有權限的您可以添加/删除等操作。</li>
            <li>只要您稍微研究下此页面还是不难熟悉的！</li>
            <li>以下可以新增管理員:</li>
            </ol></td>
      </tr>
    </tbody>
  </table>
</div>

<div>
<form name="addMember" id="addMember" action="<?php echo $editFormAction; ?>" method="POST" onsubmit="return showLayer()">
<table width="90%" id="general-table" align="center">
    <tr>
            <td class="label">管理員名稱：</td>
            <td><input type="text" name="mUser" value="" style="float:left;color:;" size="25" onChange="us_ck(this.value);"/><div id='us_ck' style="color: #F00;"></div></td>
          </tr>
          <tr>
            <td class="label">管理員密碼：</td>
            <td>
    <input type="password" name="mPassword" value="" style="float:left;color:;" size="25" />
    </td>
          </tr>
          <tr>
            <td class="label">管理員密碼重複：</td>
            <td><input type="password" name="mPassword_check" value="" style="float:left;color:;" size="25" />
    </td>
          </tr>
          <tr>
            <td class="label">管理員等級：</td>
            <td><select name="mClass" onchange="">
    <option value='1'>管理員</option>
    <option value='2'>超級管理員</option>
    <!--<option value='3'>&nbsp;&nbsp;└&nbsp;产品第二分类</option>-->
    </select>
    </td>
          </tr>
          <tr>
            <td class="label"></td>
            <td><input type="submit" value=" 新增管理員 " class="button" onClick="return validate2();" />
            </td>
          </tr>
</table>
            <input type="hidden" name="MM_insert" value="addMember">
</form>
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
mysql_free_result($showMember);
?>
