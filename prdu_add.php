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

/*if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "theForm")) {
  $insertSQL = sprintf("INSERT INTO product (pName, cId) VALUES (%s, %s)",
                       GetSQLValueString($_POST['goods_name'], "text"),
                       GetSQLValueString($_POST['cat_id'], "int"));

  mysql_select_db($database_prdu_manage, $prdu_manage);
  $Result1 = mysql_query($insertSQL, $prdu_manage) or die(mysql_error());

  $insertGoTo = "prdu_manage.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}*/

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "theForm")) {
	if((!isset($_POST['cat_id2'])) || ($_POST['cat_id2'] == NULL))
		$_POST['cat_id2']=1;
	
  $insertSQL = sprintf("INSERT INTO `product` (pName, pName2, pNo, pMaterial, pSize, pThickness, pColor, pPrice, pSheetsfee, pMaterialfee, pPrintingfee, pProcessfee, pLoss, pPackaging, pFreight, pProfits, pTaxesfee, pCartonsize, pPcs, pKg, cId, cId2) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
					   
                       GetSQLValueString($_POST['cat_id'], "int"),
					   GetSQLValueString($_POST['cat_id2'], "int"));

  mysql_select_db($database_prdu_manage, $prdu_manage);
  $Result1 = mysql_query($insertSQL, $prdu_manage) or die("產品名或編號重複! ".mysql_error());

  $insertGoTo = "prdu_manage.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  
  
  if(isset($_FILES['goods_img']['name']) || $_FILES['goods_img']['name']!=NULL){
		$query_add_pid = sprintf("SELECT * FROM `product` WHERE pName LIKE %s ",
							   GetSQLValueString($_POST['goods_name'], "text"));
		$add_pid = mysql_query($query_add_pid, $prdu_manage) or die(mysql_error());
		$row_add_pid = mysql_fetch_assoc($add_pid);
		//die($row_add_pid['pId']);
  	uploadImages($row_add_pid['pId'],$prdu_manage);
  }
  
  
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_prdu_manage, $prdu_manage);
$query_show_class = "SELECT * FROM `class` WHERE cPre=0 ORDER BY cId ";
$show_class = mysql_query($query_show_class, $prdu_manage) or die(mysql_error());
$row_show_class = mysql_fetch_assoc($show_class);
$totalRows_show_class = mysql_num_rows($show_class);

	
function uploadImages($pid, $prdu_manage) { 

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
		//echo $_FILES['upfile']['type'];		
		
	//die( "gg");
	//$upload_dir =  "../uploadFiles/";
     $upload_dir =  "upload/";
     
     $upload_file = $upload_dir .  $to = iconv("UTF-8", "big5", str_replace(' ','',$_FILES['goods_img']["name"][$i]) );     
	 
	  if(!is_dir($upload_dir))mkdir ("upload/",0644); 

	  
	  
	  /****************使用圖片壓縮lib****************/
	 /* $filename=(_UPLOADPIC($_FILES["upfile"],$maxsize=1000000,$upload_file,$newname='date'));  
     $show_pic_scal=show_pic_scal(230, 230, $filename);  
     resize($filename,$show_pic_scal[0],$show_pic_scal[1]); */
	  
     //������������������������������������������������������
     
	

	
	
	//圖片名稱寫入資料庫
	if(!isset($_FILES['goods_img']['name'][$i]) || $_FILES['goods_img']['name'][$i]==NULL)
	{$f="無圖片";}else{$f=$_FILES['goods_img']['name'][$i];}
	
			
	
  $insertSQL = sprintf("INSERT INTO `images` (iName, pId) VALUES (%s, %s)",
					   GetSQLValueString($f, "text"),				   
                       GetSQLValueString($pid, "int"));
	mysql_select_db($database_prdu_manage, $prdu_manage);
  $Result1 = mysql_query($insertSQL, $prdu_manage) or die($_FILES['goods_img']['name'][$i]." 相同圖片名稱已存在!!~  解決方法可以換一個檔案名稱重新上傳,  ".mysql_error());



	if (!move_uploaded_file($_FILES['goods_img']["tmp_name"][$i], $upload_file))die( 'upload errer');
	

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

?>
<!doctype html>
<html><head>
<meta charset="utf-8">
<title>產品新增</title>

<?php require("_include.php"); ?>

<script type="text/javascript" >
function jq_show_class(){
	$.ajax({
						url: "jq_cla.php",
						type: 'get',
						dataType: "html",
						data: "cId="+$('#cat_father').val(),
						success: function(data){							
							 $('#jq_cla').html(data);
						},
						error: function(data) { 
							alert('Ajax request 發生錯誤');
						},
    					complete: function(data) { 
						}
					});
}

$().ready(function(e) {
	
	
    /*$("#right").load("prdu_list.php");
	$("#left").load("left_menu.php");
	
	$("#cat_father").click(function(e) {
        $("#right").load("prdu_add.php");
		alert('gg');
    });
	*/
});

<!--
var editor;
KindEditor.ready(function(K) {
	editor=K.create('#Good_Detail', {
		urlType:"absolute",
		uploadJson:'../kindeditor/asp/upload_json.asp',
		fileManagerJson:'../kindeditor/asp/file_manager_json.asp',
		allowFileManager:true,
		filterMode : false
	});
});

	function trim(ui){ 
	var notValid=/(^\s)|(\s$)/; 
	while(notValid.test(ui)){ 
	ui=ui.replace(notValid,"");} 
	return ui;} 
   function validate()
   {
     var sname;
	 sname=document.getElementById("goods_name").value;
     if(trim(sname)=="")
	   { alert("商品名称不能为空"); return false; }
	 if(document.theForm.cat_id.value==0)
	   { alert("请选择商品分类"); return false; } 
		return true;
	 }
   function CheckeEditUrl(){
	//if (document.getElementById('eWebEditor1').src!='../inc/editor/ewebeditor.asp?id=Good_Detail&style=s_mini')
		//document.getElementById('eWebEditor1').src='../inc/editor/ewebeditor.asp?id=Good_Detail&style=s_mini';
     } 
   function tgclk(){
   	if(document.getElementsByName('tg_chk')[1].checked == true){
	 document.getElementById('tg1').style.display="none";
	 document.getElementById('tg2').style.display="none";
	 document.getElementById('tg3').style.display="none";
	}
	else
	{
	 document.getElementById('tg1').style.display="";
	 document.getElementById('tg2').style.display="";
	 document.getElementById('tg3').style.display="";
	}
   }
-->

</script>
<link href="css/prdu_list.css" rel="stylesheet"></link>
<link href="css/prdu_add.css" rel="stylesheet"></link>

</head>
<body>

    <?php require("_top.php"); ?>
    
    
    
    <!-- main coding-->
    <div id="right">
    
        
<h1><span class="action-span"><a href="prdu_manage.php">商品列表</a></span>網店管理 &gt;&gt; 添加新商品</h1>
<div id=tabbar-div>
    <P> <span class="tab-back" id="float-tab"><a href="prdu_manage.php" style="color:#FFF;">商品管理</a></span><span class="tab-front" id="LR-tab">新增商品</span> </P>
  </div>
  
<div class="tab-div">    

    <div class="list-div">
      <form enctype="multipart/form-data" action="<?php echo $editFormAction; ?>" method="POST" name="theForm" onSubmit="return showLayer();">
        <!-- 通用信息 -->
	        <input type="hidden" name="action" value="Add" />
        <table width="90%" id="general-table" align="center">
          <tr>
            <td class="label">商品中文名稱：</td>
            <td><input type="text" name="goods_name" value="" onKeyDown="jq_show_class();"  style="float:left;color:;" size="25" />
            <span class="require-field">*</span>
			</td>
          </tr>
          <tr>
            <td class="label">商品英文名稱：</td>
            <td><input type="text" name="goods_name2" value="" style="float:left;color:;" size="25" />
            
			</td>
          </tr>
          <tr>
            <td class="label">商品編號：</td>
            <td><input type="text" name="pno" value="" onKeyDown="jq_show_class();" style="float:left;color:;" size="20" />
            <span class="require-field">*</span>
			</td>
          </tr>
          <tr>
            <td class="label">商品分類：</td>
            <td>
                <select name="cat_id" id="cat_father" onchange="jq_show_class();" style="float:left">
                  <option value='1'>请選擇...</option><?php do { ?><option value='<?php echo $row_show_class['cId'] ?>'>&nbsp;<?php echo $row_show_class['cName'] ?></option><!--<option value='2'>&nbsp;&nbsp;├&nbsp;产品第一分类</option><option value='3'>&nbsp;&nbsp;└&nbsp;产品第二分类</option>-->
                <?php } while ($row_show_class = mysql_fetch_assoc($show_class)); ?>
                </select>
                
<!--<a href="prdu_class_add.php" title="添加分类" class="special">添加分类</a>-->

<div id="jq_cla" style="float:left"></div>
                
            </td>
          </tr>
          <tr>
            <td class="label" colspan="2"><br></td>
          </tr>
          <tr>
            <td class="label">材質：</td>
            <td><input type="text" name="pmaterial" value="" style="float:left;color:;" size="30" />
            </td>
          </tr>
          <tr>
            <td class="label">尺寸：</td>
            <td><input type="text" name="psize" value="" style="float:left;color:;" size="20" />
            <span class="require-field">(例:123*12*20mm)</span></td>
          </tr>
          <tr>
            <td class="label">厚度：</td>
            <td><input type="text" name="pthinkness" value="" style="float:left;color:;" size="15" />
            <span class="require-field">(例:0.2mm)</span></td></td>
          </tr>
          <tr>
            <td class="label">顏色：</td>
            <td><input type="text" name="pcolor" value="" style="float:left;color:;" size="20" />
            <span class="require-field">(例:白色)</span></td>
          </tr>
          <tr>
            <td class="label">單價：</td>
            <td><input type="text" name="pprice" value="" style="float:left;color:;" size="20" />
            <span class="require-field">(可加註貨幣名)</span></td>
          </tr>
          <tr>
            <td class="label">上傳商品圖片(可複選，<br><font color="#FF0000">請使用英文檔名</font>)：</td>
            <td>
              <input type="file" name="goods_img[]" size="35" multiple/><!--<img src='sys_img/no.gif' width='14' height='14' alt='否' />--></td>
          </tr>
          <tr>
            <td class="label" colspan="2"><br></td>
          </tr>
          <tr>
            <td class="label">片材費：</td>
            <td><input type="text" name="psheetsfee" value="" style="float:left;color:;" size="10" />
            <span class="require-field">元</span></td>
          </tr>
          <tr>
            <td class="label">鋪料費：</td>
            <td><input type="text" name="pmaterialfee" value="" style="float:left;color:;" size="10" />
            <span class="require-field">元</span></td>
          </tr>
          <tr>
            <td class="label">印刷費：</td>
            <td><input type="text" name="pprint" value="" style="float:left;color:;" size="10" />
            <span class="require-field">元</span></td>
          </tr>
          <tr>
            <td class="label">加工費：</td>
            <td><input type="text" name="pprocess" value="" style="float:left;color:;" size="10" />
            <span class="require-field">元</span></td>
          </tr>
          <tr>
            <td class="label">損耗：</td>
            <td><input type="text" name="ploss" value="" style="float:left;color:;" size="5" />
            <span class="require-field">％</span></td>
          </tr>
          <tr>
            <td class="label" colspan="2"><hr></td>
          </tr>
          <tr>
            <td class="label">包裝費：</td>
            <td><input type="text" name="ppacking" value="" style="float:left;color:;" size="10" />
            <span class="require-field">元</span></td>
          </tr>
          <tr>
            <td class="label">運費：</td>
            <td><input type="text" name="pfreight" value="" style="float:left;color:;" size="10" />
            <span class="require-field">元</span></td>
          </tr>
          <tr>
            <td class="label">利潤：</td>
            <td><input type="text" name="pprofits" value="" style="float:left;color:;" size="5" />
            <span class="require-field">％</span></td>
          </tr>
          <tr>
            <td class="label">稅費：</td>
            <td><input type="text" name="ptax" value="" style="float:left;color:;" size="5" />
            <span class="require-field">％</span></td>
          </tr>
          <tr>
            <td class="label" colspan="2"><br></td>
          </tr>
          <tr>
            <td class="label">紙箱尺寸：</td>
            <td><input type="text" name="pcartonsize" value="" style="float:left;color:;" size="15" />
            <span class="require-field">(例:123*12*20mm)</span></td>
          </tr>
          <tr>
            <td class="label">裝箱數量：</td>
            <td><input type="text" name="ppcs" value="" style="float:left;color:;" size="5" />
            <span class="require-field">(例:1)</span></td>
          </tr>
          <tr>
            <td class="label">裝箱重量：</td>
            <td><input type="text" name="pkg" value="" style="float:left;color:;" size="5" />
            <span class="require-field">KG</span></td>
          </tr>
        </table>

        <!-- 详细描述 -->
        

        <table width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">
    <br>
    <input type="hidden" name="goods_id" value="0" />
    <input type="submit" value=" 確定 " class="button" onclick="return validate();" />
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="reset" value=" 重置 " class="button" />
	</td>
  </tr>
</table>
        <input type="hidden" name="MM_insert" value="theForm">      
      </form>
    </div>
</div>
<script language="javascript">
  var marketPriceRate = 1.2;
  var integralPercent = 100;
  onload = function()
  {
      if (document.forms['theForm'].elements['auto_thumb'])
      {
          handleAutoThumb(document.forms['theForm'].elements['auto_thumb'].checked);
      }
	  
  }
  
  function rapidCatAdd()
  { 
    var CatAdd=window.confirm("添加分类会丢失当前页面已填写的内容，\n继续请确定！");
  	if(CatAdd)
	{
		window.location.href="Products_Cata.asp";
	}
  }
  function addImg(obj)
  {
      var src  = obj.parentNode.parentNode;
      var idx  = rowindex(src);
      var tbl  = document.getElementById('gallery-table');
      var row  = tbl.insertRow(idx + 1);

      var cell = row.insertCell(-1);
      cell.innerHTML = src.cells[0].innerHTML.replace(/(.*)(addImg)(.*)(\[)(\+)/i, "$1removeImg$3$4-");
  }

  /**
   * 删除图片上传
   */
  function removeImg(obj)
  {
      var row = rowindex(obj.parentNode.parentNode);
      var tbl = document.getElementById('gallery-table');

      tbl.deleteRow(row);
  }
  
  function computePrice(inputName, rate, priceName)
  {
      var shopPrice = priceName == undefined ? document.forms['theForm'].elements['shop_price'].value : document.forms['theForm'].elements[priceName].value;
      shopPrice = Utils.trim(shopPrice) != '' ? parseFloat(shopPrice)* rate : 0;
      shopPrice += "";

      n = shopPrice.lastIndexOf(".");
      if (n > -1)
      {
          shopPrice = shopPrice.substr(0, n + 3);
      }

      if (document.forms['theForm'].elements[inputName] != undefined)
      {
          document.forms['theForm'].elements[inputName].value = shopPrice;
      }
      else
      {
          document.getElementById(inputName).value = shopPrice;
      }
  }
  
    function marketPriceSetted()
  {
    computePrice('shop_price', 1/marketPriceRate, 'market_price');
    
        
  }
  
  function priceSetted()
  {
    computePrice('market_price', marketPriceRate);
        
        
  }

  /**
   * 设置会员价格注释
   */
  function set_price_note(rank_id)
  {
    var shop_price = parseFloat(document.forms['theForm'].elements['shop_price'].value);

    var rank = new Array();
    
        
    if (shop_price >0 && rank[rank_id] && document.getElementById('rank_' + rank_id) && parseInt(document.getElementById('rank_' + rank_id).value) == -1)
    {
      var price = parseInt(shop_price * rank[rank_id] + 0.5) / 100;
      if (document.getElementById('nrank_' + rank_id))
      {
        document.getElementById('nrank_' + rank_id).innerHTML = '(' + price + ')';
      }
    }
    else
    {
      if (document.getElementById('nrank_' + rank_id))
      {
        document.getElementById('nrank_' + rank_id).innerHTML = '';
      }
    }
  }
  
 function integral_market_price()
  {
    document.forms['theForm'].elements['market_price'].value = parseInt(document.forms['theForm'].elements['market_price'].value);
  }
  
  function handleAutoThumb(checked)
  {
      document.forms['theForm'].elements['goods_thumb'].disabled = checked;
  }
  
   /**
   * 新增一个规格
   */
  function addSpec(obj)
  {
      var src   = obj.parentNode.parentNode;
      var idx   = rowindex(src);
      var tbl   = document.getElementById('attrTable');
      var row   = tbl.insertRow(idx + 1);
      var cell1 = row.insertCell(-1);
      var cell2 = row.insertCell(-1);
      var regx  = /<a([^>]+)<\/a>/i;

      cell1.className = 'label';
      cell1.innerHTML = src.childNodes[0].innerHTML.replace(/(.*)(addSpec)(.*)(\[)(\+)/i, "$1removeSpec$3$4-");
      cell2.innerHTML = src.childNodes[1].innerHTML.replace(/readOnly([^\s|>]*)/i, '');
  }

  /**
   * 删除规格值
   */
  function removeSpec(obj)
  {
      var row = rowindex(obj.parentNode.parentNode);
      var tbl = document.getElementById('attrTable');

      tbl.deleteRow(row);
  }
</script>

<iframe style="display:none" name="temp_frame" ></iframe>

<script src="../js/Tab.js" type=text/javascript></script>

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
