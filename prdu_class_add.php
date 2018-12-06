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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addCataForm")) {
  $insertSQL = sprintf("INSERT INTO `class` (cName,cPre) VALUES (%s,%s)",
                       GetSQLValueString($_POST['CataName'], "text"),
					   GetSQLValueString($_GET['cpre'], "int"));

  mysql_select_db($database_prdu_manage, $prdu_manage);
  $Result1 = mysql_query($insertSQL, $prdu_manage) or die(mysql_error());

  $insertGoTo = "prdu_class.php";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $insertGoTo));
}

//header("Location: login.php");
//echo "login success";
?>
<!doctype html>
<html><head>
<meta charset="utf-8">
<title>新增類別</title>

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
	
    <h1>網店管理  >> 商品分類管理 <span class="action-span"><a href="<?php echo $_SERVER['HTTP_REFERER'];?>">回上一頁</a></span></h1>
  <div id=tabbar-div>
    <P> <span class="tab-back" id="float-tab"><a href="prdu_class.php">商品分類管理</a></span><span class="tab-front" id="LR-tab">添加<?php if($_GET['cpre']==0)echo '父'?>分類</span> </P>
  </div>
  <div class="list-div">
  
  <table width="90%" align="center" id="float-table" >
    <form action="<?php echo $editFormAction; ?>" method="POST" name="addCataForm" id="addCataForm" onsubmit="return showLayer();">
      <tbody><!--
        <tr>
          <th >添加一级分类</th>
        </tr>-->
        <tr>
          <td height="20px"></td>
        </tr>
        <tr>
          <td align="center" valign="middle">類别名稱：              
            <input type="hidden" name="ParentID" value="0" />
            <input type="text" name="CataName" /></td>
          </tr>
        
                <!--<tr>
          <td align="center" valign="middle"><span style="color:#FF0000">以下是栏目内商品的配送价格,请详细填写！</span></td>
          </tr>

         <tr>
          <td align="center" valign="middle">快递：￥<input name='KuaiDi' type='text' onkeyup="this.value=this.value.replace(/[^\d]/g,'');" value="0" size="5" maxlength="3"/>元&nbsp;
              EMS：￥<input name='EMS' type='text' onkeyup="this.value=this.value.replace(/[^\d]/g,'');" value="0" size="5" maxlength="3"/>元&nbsp;
            平邮：￥<input name='PingYou' type='text' onkeyup="this.value=this.value.replace(/[^\d]/g,'');" value="0" size="5" maxlength="3"/>元&nbsp;
            配送4：￥<input name='ps4' type='text' onkeyup="this.value=this.value.replace(/[^\d]/g,'');" value="0" size="5" maxlength="3"/>元&nbsp;
            配送5：￥<input name='ps5' type='text' onkeyup="this.value=this.value.replace(/[^\d]/g,'');" value="0" size="5" maxlength="3"/>元</td>
          </tr>-->
         <tr>
          <td align="center" valign="middle"><div class="button-div">
            <input name="button2" type="submit" class="button" id="button2" value="添加" />&nbsp;<input name="reSet" type="reset" class="button" id="reSet" value="重置" />
          </div></td>
        </tr>
        <tr>
          <td colspan="2" height="20px"></td>
        </tr>
      </tbody>
      <input type="hidden" name="MM_insert" value="addCataForm">
    </form>
  </table>
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
