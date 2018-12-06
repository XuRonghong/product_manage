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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "addCataForm")) {
  $updateSQL = sprintf("UPDATE `class` SET cName=%s,cPre=%s WHERE cId=%s",
                       GetSQLValueString($_POST['CataName'], "text"),
					   GetSQLValueString($_POST['mClass'], "text"),
                       GetSQLValueString($_POST['ParentID'], "int"));

  mysql_select_db($database_prdu_manage, $prdu_manage);
  $Result1 = mysql_query($updateSQL, $prdu_manage) or die(mysql_error());

  $updateGoTo = "prdu_class.php";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $updateGoTo));
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addMember")) {	
  $insertSQL = sprintf("UPDATE `member` SET  mPassword=%s, mLayer=%s WHERE mId= %s", 
                       GetSQLValueString($_POST['mPassword'], "text"),
                       GetSQLValueString($_POST['mClass'], "int"),
					   GetSQLValueString($_POST["MM_insertID"], "int"));

  mysql_select_db($database_prdu_manage, $prdu_manage);
  $Result1 = mysql_query($insertSQL, $prdu_manage) or die(mysql_error());
  
  $updateGoTo = "member.php";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
	if($_SESSION['MM_UserGroup']!=2)
		$updateGoTo = "prdu_manage.php";
	
  header(sprintf("Location: %s", $updateGoTo));
}


if ((isset($_POST["MM_insert2"])) && ($_POST["MM_insert2"] == "theForm")) {
  $insertSQL = sprintf("UPDATE `product` SET pName=%s, pName2=%s, pNo=%s, pMaterial=%s, pSize=%s, pThickness=%s, pColor=%s, pPrice=%s, pSheetsfee=%s, pMaterialfee=%s, pPrintingfee=%s, pProcessfee=%s, pLoss=%s, pPackaging=%s, pFreight=%s, pProfits=%s, pTaxesfee=%s, pCartonsize=%s, pPcs=%s, pKg=%s 
  						
						WHERE pId= %s ",
                       GetSQLValueString($_POST['goods_name'], "text"),				   
					   GetSQLValueString($_POST['goods_name2'], "text"),
					   GetSQLValueString($_POST['pno'], "text"),
					   
					   GetSQLValueString($_POST['pmaterial'], "text"),
					   GetSQLValueString($_POST['psize'], "text"),
					   GetSQLValueString($_POST['pthinkness'], "text"),
					   GetSQLValueString($_POST['pcolor'], "text"),
					   GetSQLValueString($_POST['pprice'], "text"),
					   
					   GetSQLValueString($_POST['psheetsfee'], "double"),
					   GetSQLValueString($_POST['pmaterialfee'], "double"),
					   GetSQLValueString($_POST['pprint'], "double"),
					   GetSQLValueString($_POST['pprocess'], "double"),
					   GetSQLValueString($_POST['ploss'], "int"),
					   
					   GetSQLValueString($_POST['ppacking'], "double"),
					   GetSQLValueString($_POST['pfreight'], "double"),
					   GetSQLValueString($_POST['pprofits'], "int"),
					   GetSQLValueString($_POST['ptax'], "int"),
					   
					   GetSQLValueString($_POST['pcartonsize'], "text"),
					   GetSQLValueString($_POST['ppcs'], "int"),
					   GetSQLValueString($_POST['pkg'], "int"),					   
					   
                       GetSQLValueString($_POST['goods_id'], "int"));

  mysql_select_db($database_prdu_manage, $prdu_manage);
  $Result1 = mysql_query($insertSQL, $prdu_manage) or die("產品名或編號重複! ".mysql_error());
  
  
  
	
	
	if(isset($_FILES['goods_img']['name']) || $_FILES['goods_img']['name']!=NULL){
		$query_add_pid = sprintf("SELECT * FROM `product` WHERE pName LIKE %s ",
							   GetSQLValueString($_POST['goods_name'], "text"));
		$add_pid = mysql_query($query_add_pid, $prdu_manage) or die(mysql_error());
		$row_add_pid = mysql_fetch_assoc($add_pid);
		//die($row_add_pid['pId']);
		uploadImages($row_add_pid['pId'],$prdu_manage);
	 }
	 
	

  
		//if($_SERVER['HTTP_REFERER']==($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'])){
  $self = $_SERVER['PHP_SELF'];
	/*if (isset($_SERVER['QUERY_STRING'])) {
	  $self .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}*/
	$self .= "?id=".$_GET['id']."&data=P";
		/*}else{
			
		}*/
  header(sprintf("Location: %s", $self));
}





$colname_edit_data = "-1";
if (isset($_GET['id'])) {
  $colname_edit_data = $_GET['id'];
}

if($_GET['data']=="C"){
mysql_select_db($database_prdu_manage, $prdu_manage);
$query_edit_data = sprintf("SELECT * FROM `class` WHERE cId = %s", GetSQLValueString($colname_edit_data, "int"));
$edit_data = mysql_query($query_edit_data, $prdu_manage) or die(mysql_error());
$row_edit_data = mysql_fetch_assoc($edit_data);
$totalRows_edit_data = mysql_num_rows($edit_data);

$query_show_class = "SELECT * FROM `class` WHERE cPre=0";
$show_class = mysql_query($query_show_class, $prdu_manage) or die(mysql_error());
//$row_show_class = mysql_fetch_assoc($show_class);
$totalRows_show_class = mysql_num_rows($show_class);


}else if($_GET['data']=="P"){
	mysql_select_db($database_prdu_manage, $prdu_manage);
	$query_edit_data = sprintf("SELECT * FROM `product` join `class` on product.cId=class.cId WHERE pId = %s", GetSQLValueString($colname_edit_data, "int"));
	$edit_data = mysql_query($query_edit_data, $prdu_manage) or die(mysql_error());
	$row_edit_data = mysql_fetch_assoc($edit_data);
	$totalRows_edit_data = mysql_num_rows($edit_data);
	
	
	  
	//**列出所有要編輯圖片**
	$query_show_edit_img = sprintf("SELECT * FROM `images` WHERE pId = %s", GetSQLValueString($colname_edit_data, "int"));
	$show_edit_img = mysql_query($query_show_edit_img, $prdu_manage) or die(mysql_error());
	//$row_show_edit_img = mysql_fetch_assoc($show_edit_img);
	$totalRows_show_edit_img = mysql_num_rows($show_edit_img);
	
	
	
}else if($_GET['data']=="M"){
	mysql_select_db($database_prdu_manage, $prdu_manage);
	$query_edit_data = sprintf("SELECT * FROM `member` WHERE mId = %s", GetSQLValueString($colname_edit_data, "int"));
	$edit_data = mysql_query($query_edit_data, $prdu_manage) or die(mysql_error());
	$row_edit_data = mysql_fetch_assoc($edit_data);
	$totalRows_edit_data = mysql_num_rows($edit_data);

}



//****upload-image
function uploadImages($pid, $prdu_manage) { 

//上传文件类型列表  
$uptypes=array(  
    'image/jpg',  
    'image/jpeg',  
    'image/png',  
    'image/pjpeg',  
    'image/gif',  
    'image/bmp',  
    'image/x-png'  
);  

//$editFormAction = "prdu_manage.php";//$_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


//if ((isset($_POST["action"])) && ($_POST["action"] == "Add")) {
	
	$i=0;
	//die($_FILES['files']["name"][0]."-gg".$_FILES['files']["name"][1]);
while( $_FILES['goods_img']["name"][$i] ){
	
	//上傳圖片
	if($_FILES['goods_img']['size'][$i]>900000){
		die( '上傳檔案錯誤，檔案大小可能太大');	
	}
	else if($_FILES['goods_img']['size'][$i]>0){
		
		
		if(!in_array($_FILES['goods_img']['type'][$i], $uptypes))  
		//检查文件类型  
		{  
			echo $_FILES['goods_img']["name"][$i] . "文件類型不符! File type does not match!".$_FILES['goods_img']['type'][$i];  
			exit;  
		}  
		
		//echo $_FILES['upfile']['type'];		
		
	//die( "gg");
	//$upload_dir =  "../uploadFiles/";
     $upload_dir =  "upload/";
     
     $upload_file = $upload_dir .  $to = iconv("UTF-8", "big5", str_replace(' ','',$_FILES['goods_img']["name"][$i]) );     
	 
	  if(!is_dir($upload_dir))mkdir ("upload/",0644); 

	  
	  
	  /*if (file_exists($upload_file) && $overwrite != true)  
		{  
			echo "同名文件已经存在了 The same file already exists";  
			exit;  
		}  */
	  
	  /****************使用圖片壓縮lib****************/
	 /* $filename=(_UPLOADPIC($_FILES["upfile"],$maxsize=1000000,$upload_file,$newname='date'));  
     $show_pic_scal=show_pic_scal(230, 230, $filename);  
     resize($filename,$show_pic_scal[0],$show_pic_scal[1]); */
	  
     //������������������������������������������������������
     if (!move_uploaded_file($_FILES['goods_img']["tmp_name"][$i], $upload_file))die( 'upload errer');
	

	
	
	//圖片名稱寫入資料庫
	if(!isset($_FILES['goods_img']['name'][$i]) || $_FILES['goods_img']['name'][$i]==NULL)
	{$f="無圖片";}else{$f=$_FILES['goods_img']['name'][$i];}
	
			
	$f = str_replace(' ','',$f);
  $insertSQL = sprintf("INSERT INTO `images` (iName, pId) VALUES (%s, %s)",
					   GetSQLValueString($f, "text"),				   
                       GetSQLValueString($pid, "int"));
	mysql_select_db($database_prdu_manage, $prdu_manage);
  $Result1 = mysql_query($insertSQL, $prdu_manage) or die($_FILES['goods_img']['name'][$i]." 相同圖片名稱已存在!!~  解決方法可以換一個檔案名稱重新上傳,  ".mysql_error());

  /*$insertGoTo = "adminindex.php?gmId=" . $_SESSION['gmId'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  
  		}//if-end
  
  $i++;
	}//while-end
  //header(sprintf("Location: %s", $editFormAction));
}


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

function checkDel(iid){
		$.ajax({
			url: 'del.php',
			cache: false,
			dataType: 'html',
				type:'GET',
			data: "id="+iid+
				  "&data=I",

			error: function(xhr) {
			  alert('Ajax request 發生錯誤');
			},
			success: function(response) {
					// alert(response);
				   // $('#fragment-1').load("delete_group.php");
				  // alert(response);
					window.location.reload();

					//$('#fragment-1').load('website.php');
					
					//$(".add_ajax").load(location.href + " .add_ajax");
			}
		}); 
		//alert("g"+iid);
		return true;	
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

		/*us_ck(document.addMember.mUser.value);
		
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
		else*/ if(document.addMember.mPassword.value=="")
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
</script>
<link href="css/prdu_list.css" rel="stylesheet"></link>
<style type="text/css">
#LR-table a{text-decoration:none}
#LR-table a:hover{text-decoration:none}
#float-tab a{color:#FFF;}

.xxsmall_pic{
	 width: 40px;
	 height:40px;
	 border:0;	 
	 margin-top: 3px;
	 margin-left: 3px;
}
</style>


</head>
<body>

    <?php require("_top.php"); ?>
    
    <!-- main coding-->
    <div id="right">
	
 
    
    <?php if($_GET['data']=="C"){ ?>

  <script>
document.title = "修改分类";
</script>    

   <h1><span class="action-span"><a href="<?php echo $_SERVER['HTTP_REFERER'];?>">回上一頁</a></span>網店管理 &gt;&gt; 商品詳細資料</h1>

<div class="tab-div">
  <div id=tabbar-div>
    <P> <span class="tab-back" id="float-tab"><a href="prdu_class.php">商品分類管理</a></span><span class="tab-front" id="LR-tab">修改分類</span> </P>
  </div>
  <div class="list-div">
  <table width="90%" align="center" id="float-table" >
    <form method="POST" name="addCataForm" action="<?php echo $editFormAction; ?>" id="addCataForm" onsubmit="return showLayer();">
      <tbody><!--
        <tr>
          <th >添加一级分类</th>
        </tr>-->
        <tr>
          <td height="20px"></td>
        </tr>
        <tr>
          <td align="center" valign="middle">類别名稱：              
            <input type="hidden" name="ParentID" value="<?php echo $row_edit_data['cId'];?>" />
            <input type="text" name="CataName" value="<?php echo $row_edit_data['cName'];?>"/></td>
          </tr>
          <tr>
          <td align="center" valign="middle">父親類别：
            <select name="mClass" onchange="" style="width:150px;">
            <?php if(isset($_GET['cpre']) && $_GET['cpre']!=0){ ?>
            
<?php while($row_show_class = mysql_fetch_assoc($show_class)){ ?>            
    <option value='<?php echo $row_show_class['cId'];?>' 
	<?php if($row_show_class['cId']==$_GET['cpre'])echo "selected"?>><?php echo $row_show_class['cName'];?></option>
<?php } ?>

			<?php }else{?>
    <option value='0'></option>
            <?php }?>
    		</select>
    		</td>
          </tr>
         <tr>
          <td align="center" valign="middle"><div class="button-div">
            <input name="button2" type="submit" class="button" id="button2" value="修改" />
          </div></td>
        </tr>
        <tr>
          <td colspan="2" height="20px"></td>
        </tr>
      </tbody>
      <input type="hidden" name="MM_insert" value="addCataForm">
      <input type="hidden" name="MM_update" value="addCataForm">
    </form>
  </table>
  
  </div>
</div>

<?php }else if($_GET['data']=="M"){ ?>
  
  <script>
document.title = "管理員設置";
</script>

<h1><span class="action-span"><a href="<?php echo $_SERVER['HTTP_REFERER'];?>">回上一頁</a></span>網店管理 &gt;&gt; 管理員設置</h1>

  <div id=tabbar-div>
    <P> <span class="tab-back" id="float-tab"><a href="member.php">多管理員設置</a></span><span class="tab-front" id="LR-tab">修改管理員</span> </P>
  </div>
  
<form name="addMember" id="addMember" action="<?php echo $editFormAction; ?>" method="POST" onsubmit="return showLayer()">
<table width="90%" id="general-table" align="center">
    <tr>
            <td class="label">管理員名稱：</td>
            <td><input type="text" name="mUser" value="<?php echo $row_edit_data['mUser'];?>" disabled="true" size="25"/><div id='us_ck' style="color: #F00;"></div></td>
          </tr>
          <tr>
            <td class="label">管理員密碼：</td>
            <td>
    <input type="password" name="mPassword" value="<?php echo $row_edit_data['mPassword'];?>" style="float:left;color:;" size="25" />
    </td>
          </tr>
          <tr>
            <td class="label">管理員密碼重複：</td>
            <td><input type="password" name="mPassword_check" value="<?php echo $row_edit_data['mPassword'];?>" style="float:left;color:;" size="25" />
    </td>
          </tr>
          <tr>
            <td class="label">管理員等級：</td>
            <td>
            
            <select name="mClass" onchange="" <?php if($_SESSION['MM_UserGroup']!=2)echo "disabled='true'";?>>
    <option value='1' <?php if($row_edit_data['mLayer']==1)echo 'selected';?>>管理員</option>
    <option value='2' <?php if($row_edit_data['mLayer']==2)echo 'selected';?>>超級管理員</option>
    <!--<option value='3'>&nbsp;&nbsp;└&nbsp;产品第二分类</option>-->
    </select>
    </td>
          </tr>
          <tr>
            <td class="label"></td>
            <td><input type="submit" value=" 修改管理員 " class="button" onClick="return validate2();" />
            </td>
          </tr>
</table>
            <input type="hidden" name="MM_insert" value="addMember">
            <input type="hidden" name="MM_insertID" value="<?php echo $row_edit_data['mId'];?>">
</form>


<?php }else if($_GET['data']=="P"){?>

  <script>
document.title = "修改商品";
</script>  

<h1><span class="action-span"><a href="<?php echo $_SERVER['HTTP_REFERER'];//"Product_ok.php?id=".$_GET['id'];?>">回上一頁</a></span>網店管理 &gt;&gt; 商品管理</h1>

<div id=tabbar-div>
    <P> <span class="tab-back" id="float-tab"><a href="prdu_manage.php">商品管理</a></span><span class="tab-front" id="LR-tab">修改商品</span> </P>
  </div>

<form enctype="multipart/form-data" action="<?php echo $editFormAction; ?>" method="POST" name="theForm" onSubmit="return showLayer();">
        <!-- 通用信息 -->
	        <input type="hidden" name="action" value="Add" />
        <table width="90%" id="general-table" align="center">
          <tr>
            <td class="label">商品中文名稱：</td>
            <td><input type="text" name="goods_name" value="<?php echo $row_edit_data['pName'];?>" style="float:left;color:;" size="25" />
            <span class="require-field">*</span>
			</td>
          </tr>
          <tr>
            <td class="label">商品英文名稱：</td>
            <td><input type="text" name="goods_name2" value="<?php echo $row_edit_data['pName2'];?>" style="float:left;color:;" size="25" />
            
			</td>
          </tr> 
          <tr>
            <td class="label">商品編號：</td>
            <td><input type="text" name="pno" value="<?php echo $row_edit_data['pNo'];?>" style="float:left;color:;" size="20" />
            <span class="require-field">*</span>
			</td>
          </tr>         
          <tr>
            <td class="label">商品分類：</td>
            <td>
                <select name="cat_id" onchange="" disabled="true">
                  <option value='<?php echo $row_edit_data['cId'];?>'><?php echo $row_edit_data['cName'];?></option>
                </select>
                
                
<?php
$query_edit_data2 = sprintf("SELECT * FROM `class` WHERE cId = %s", GetSQLValueString($row_edit_data['cId2'], "int"));
$edit_data2 = mysql_query($query_edit_data2, $prdu_manage) or die(mysql_error());
$row_edit_data2 = mysql_fetch_assoc($edit_data2);
?>                
                <select name="cat_id2" onchange="" disabled="true">
                  <option value='<?php echo $row_edit_data2['cId2'];?>'><?php echo $row_edit_data2['cName'];?></option>
                </select>
<!--<a href="prdu_class_add.php" title="添加分类" class="special">添加分类</a>
                              <span class="require-field">*</span>  -->          </td>
          </tr>
          <tr>
            <td class="label" colspan="2"><br></td>
          </tr>
          <tr>
            <td class="label">材質：</td>
            <td><input type="text" name="pmaterial" value="<?php echo $row_edit_data['pMaterial'];?>" style="float:left;color:;" size="30" />
            </td>
          </tr>
          <tr>
            <td class="label">尺寸：</td>
            <td><input type="text" name="psize" value="<?php echo $row_edit_data['pSize'];?>" style="float:left;color:;" size="20" />
            <span class="require-field">(例:123*12*20mm)</span></td>
          </tr>
          <tr>
            <td class="label">厚度：</td>
            <td><input type="text" name="pthinkness" value="<?php echo $row_edit_data['pThickness'];?>" style="float:left;color:;" size="15" />
            <span class="require-field">(例:0.2mm)</span></td></td>
          </tr>
          <tr>
            <td class="label">顏色：</td>
            <td><input type="text" name="pcolor" value="<?php echo $row_edit_data['pColor'];?>" style="float:left;color:;" size="20" />
            <span class="require-field">(例:白色)</span></td>
          </tr>
          <tr>
            <td class="label">單價：</td>
            <td><input type="text" name="pprice" value="<?php echo $row_edit_data['pPrice'];?>" style="float:left;color:;" size="20" />
            <span class="require-field">(可加註貨幣名)</span></td>
          </tr>
          <tr>
            <td class="label" colspan="2"><br></td>
          </tr>
          <tr>
            <td class="label">片材費：</td>
            <td><input type="text" name="psheetsfee" value="<?php echo $row_edit_data['pSheetsfee'];?>" style="float:left;color:;" size="10" />
            <span class="require-field">元</span></td>
          </tr>
          <tr>
            <td class="label">鋪料費：</td>
            <td><input type="text" name="pmaterialfee" value="<?php echo $row_edit_data['pMaterialfee'];?>" style="float:left;color:;" size="10" />
            <span class="require-field">元</span></td>
          </tr>
          <tr>
            <td class="label">印刷費：</td>
            <td><input type="text" name="pprint" value="<?php echo $row_edit_data['pPrintingfee'];?>" style="float:left;color:;" size="10" />
            <span class="require-field">元</span></td>
          </tr>
          <tr>
            <td class="label">加工費：</td>
            <td><input type="text" name="pprocess" value="<?php echo $row_edit_data['pProcessfee'];?>" style="float:left;color:;" size="10" />
            <span class="require-field">元</span></td>
          </tr>
          <tr>
            <td class="label">損耗：</td>
            <td><input type="text" name="ploss" value="<?php echo $row_edit_data['pLoss'];?>" style="float:left;color:;" size="5" />
            <span class="require-field">％</span></td>
          </tr>
          <tr>
            <td class="label" colspan="2"><hr></td>
          </tr>
          <tr>
            <td class="label">包裝費：</td>
            <td><input type="text" name="ppacking" value="<?php echo $row_edit_data['pPackaging'];?>" style="float:left;color:;" size="10" />
            <span class="require-field">元</span></td>
          </tr>
          <tr>
            <td class="label">運費：</td>
            <td><input type="text" name="pfreight" value="<?php echo $row_edit_data['pFreight'];?>" style="float:left;color:;" size="10" />
            <span class="require-field">元</span></td>
          </tr>
          <tr>
            <td class="label">利潤：</td>
            <td><input type="text" name="pprofits" value="<?php echo $row_edit_data['pProfits'];?>" style="float:left;color:;" size="5" />
            <span class="require-field">％</span></td>
          </tr>
          <tr>
            <td class="label">稅費：</td>
            <td><input type="text" name="ptax" value="<?php echo $row_edit_data['pTaxesfee'];?>" style="float:left;color:;" size="5" />
            <span class="require-field">％</span></td>
          </tr>
          <tr>
            <td class="label" colspan="2"><br></td>
          </tr>
          <tr>
            <td class="label">紙箱尺寸：</td>
            <td><input type="text" name="pcartonsize" value="<?php echo $row_edit_data['pCartonsize'];?>" style="float:left;color:;" size="15" />
            <span class="require-field">(例:123*12*20mm)</span></td>
          </tr>
          <tr>
            <td class="label">裝箱數量：</td>
            <td><input type="text" name="ppcs" value="<?php echo $row_edit_data['pPcs'];?>" style="float:left;color:;" size="5" />
            <span class="require-field">(例:1)</span></td>
          </tr>
          <tr>
            <td class="label">裝箱重量：</td>
            <td><input type="text" name="pkg" value="<?php echo $row_edit_data['pKg'];?>" style="float:left;color:;" size="5" />
            <span class="require-field">KG</span></td>
          </tr>
          <tr>
            <td class="label" colspan="2"><br></td>
          </tr>
          <tr>
            <td class="label">新增商品图片<font color="#FF0000">(請使用英文檔名)</font>：</td>
            <td>
              <input type="file" name="goods_img[]" size="35" multiple/><!--<img src='sys_img/no.gif' width='14' height='14' alt='否' />--></td>
          </tr>
          <tr>
            <td class="label" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;刪除商品图片<hr></td>
            <td><h5>&nbsp;&nbsp;一鍵刪除</h5></td>
          </tr>
          <tr><th colspan="2">
	<?php     if($totalRows_show_edit_img==0)echo "<h4>無圖片</h4>" ?>
    	  </th></tr>
    
<?php   while($row_show_edit_img = mysql_fetch_assoc($show_edit_img)){  ?>
          <tr>
          	<td class="label">
            <a href="upload/<?php echo $row_show_edit_img['iName'];?>">
    <img src="upload/<?php echo $row_show_edit_img['iName'];?>" class="xxsmall_pic">
    </a>
    		</td>
            <td><?php echo $row_show_edit_img['iName']; ?></td>
            <th>
            <input name="checkall" type="checkbox" id="checkall" onclick="checkDel(this.value);" value="<?php echo $row_show_edit_img['iId']; ?>"/>
            </th>
          </tr>          
<?php   } ?>

        </table>

        <!-- 详细描述 -->
        

        <table width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">
    <input type="hidden" name="goods_id" value="<?php echo $row_edit_data['pId'];?>" />
    <input type="submit" value=" 確定 " class="button" onclick="return validate();" />
    <input type="reset" value=" 重置 " class="button" />
	</td>
  </tr>
</table>
        <input type="hidden" name="MM_insert2" value="theForm">      
      </form>
      
      <?php  };?>



<div class="list-div" style="margin-top:20px">
  <table width="90%" align="center"  id="LR-table" >
    <tbody>
      <tr>
        <!--<th>商品分类管理说明</th>-->
      </tr>
      <tr>
        <td class="footer"><ol>
            <!--<li>在这里您可以建立商品分类（支持三级分类），只有先添加好分类后才可以添加商品。</li>-->
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
//mysql_free_result($edit_data);
?>
