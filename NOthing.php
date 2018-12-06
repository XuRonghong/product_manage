<?php require_once('Connections/prdu_manage.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
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
<title>你沒有權限存取</title>

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
    
<h1>你沒有權限存取!</h1>

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
