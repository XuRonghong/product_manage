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
?>
<?php

ob_start();
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}/*

if (isset($_SESSION['us']) && isset($_SESSION['MM_Username'])) {
  header("Location: " . "prdu_manage.php" );
}*/



$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['AdminUser'])) {
  $loginUsername=$_POST['AdminUser'];
  $password=$_POST['AdminPwd'];
  $MM_fldUserAuthorization = "mLayer";
  $MM_redirectLoginSuccess = "prdu_manage.php";
  $MM_redirectLoginFailed = "logout.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_prdu_manage, $prdu_manage);
  	
  $LoginRS__query=sprintf("SELECT mId,mUser, mPassword, mLayer FROM member WHERE mUser=%s AND mPassword=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $prdu_manage) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'mLayer');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	
	$_SESSION['us'] = mysql_result($LoginRS,0,'mId');      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
	$loginFailed = "帳號或密碼錯誤";
  }
}

ob_flush();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>管理系統登入</title>
<style type="text/css">
<!--
BODY {PADDING: 0px;MARGIN: 0px; FONT-size: 12px; background:#278296;}
input.button {
  height:21px;
  padding: 2px 8px 0px;
  margin: 2px 2px;
  border: 1px solid #2D5082;
  /*background: url(/webAdmin/sys_img/button_bg.gif) repeat-x; */
}
-->
</style>
<SCRIPT LANGUAGE="JavaScript">
function check_login()
	{
		if(document.LoginForm.AdminUser.value=="")
		{
			alert("请输入用户名！");
			document.LoginForm.AdminUser.focus();
			return false
		}
		if(/[^a-zA-Z0-9\-]/g.test(document.LoginForm.AdminUser.value) /*|| /[^a-zA-Z]/g.test(document.LoginForm.AdminUser.value.substr(0,1))*/)
		{
			alert("用户名只能用数字或英文,字母开头!");
			document.form_login.AdminUser.focus();
			return false;
		}
		if(document.LoginForm.AdminPwd.value=="")
		{
			alert("请输入密码！");
			document.LoginForm.AdminPwd.focus();
			return false
		}
     return showLayer();
	}
</SCRIPT>

</head>

<body>
<form id="LoginForm" name="LoginForm" method="POST" action="<?php echo $loginFormAction; ?>"   onsubmit="return check_login()">
  <table cellspacing="0" cellpadding="0" style="margin-top: 100px" align="center">
    <tr>
      <td><img src="images/logo1.gif" width="178" height="256" border="0" alt="ZWWEB" /></td>
      <td style="padding-left: 50px"><table>
          <tr>
            <td>帳號：</td>
            <td><input name="AdminUser" type="text" id="AdminUser" size="16" maxlength="32" style="border:1px solid #278296" /></td>
          </tr>
          <tr>
            <td>密碼：</td>
            <td><input name="AdminPwd" type="password" id="AdminPwd" size="16" maxlength="32" style="border:1px solid #278296" /></td>
          </tr>
          <tr>
            <td></td>
            <td>
			<!--<input id="check_code" maxlength="4" size="4" name="check_code"  style="position:relative; top:-5px; border:1px solid #278296" />
			<img id="ImgCheckCode" style="BORDER: #000 1px solid;" src="VerifyCode.asp" /> --></td>
          </tr>
          <tr>
            <td colspan="2" align="center"><input type="submit" value="登入管理" class="button" /></td>
          </tr>
		  
      </table></td>
    </tr>
  </table>
</form>

</body>
</html>
<iframe style="display:none" name="temp_frame" ></iframe>

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
