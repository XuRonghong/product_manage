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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "theForm")) {
	die('gg');
  $insertSQL = sprintf("INSERT INTO `product` (pName, cId) VALUES (%s, %s)",
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
}

$colname_prdu_one = "-1";
if (isset($_GET['id'])) {
  $colname_prdu_one = $_GET['id'];
}
mysql_select_db($database_prdu_manage, $prdu_manage);
$query_prdu_one = sprintf("SELECT * FROM product JOIN class ON product.cId=class.cId 
WHERE pId = %s", GetSQLValueString($colname_prdu_one, "int"));
$prdu_one = mysql_query($query_prdu_one, $prdu_manage) or die(mysql_error());
$row_prdu_one = mysql_fetch_assoc($prdu_one);
$totalRows_prdu_one = mysql_num_rows($prdu_one);

$query_prdu_one_cid2 = sprintf("SELECT cName FROM product JOIN class ON product.cId2=class.cId 
WHERE pId = %s", GetSQLValueString($colname_prdu_one, "int"));
$prdu_one_cid2 = mysql_query($query_prdu_one_cid2, $prdu_manage) or die(mysql_error());
$row_prdu_one_cid2 = mysql_fetch_assoc($prdu_one_cid2);




/*$query_prdu_onepic = sprintf("SELECT * FROM product LEFT JOIN images ON product.pId=images.pId WHERE product.pId = %s", GetSQLValueString($colname_prdu_one, "int"));*/
$query_prdu_onepic = sprintf("SELECT * FROM images WHERE pId = %s", GetSQLValueString($colname_prdu_one, "int"));
$prdu_onepic = mysql_query($query_prdu_onepic, $prdu_manage) or die(mysql_error());
$row_prdu_onepic = mysql_fetch_assoc($prdu_onepic);
$totalRows_prdu_onepic = mysql_num_rows($prdu_onepic);


						

//$editFormAction = "prdu_manage.php";//$_SERVER['PHP_SELF'];
/*if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if ((isset($_POST["action"])) && ($_POST["action"] == "Add")) {
	
	$i=0;
	//die($_FILES['files']["name"][0]."-gg".$_FILES['files']["name"][1]);
while( $_FILES['goods_img']["name"][$i]){
	
	//上傳圖片
	if($_FILES['goods_img']['size'][$i]>10000000){
		die( '上傳檔案錯誤，可能太大');	
	}
	else if($_FILES['goods_img']['size'][$i]>0){
		//echo $_FILES['upfile']['type'];		
		
	//die( "gg");
	//$upload_dir =  "../uploadFiles/";
     $upload_dir =  "upload/";
     
     $upload_file = $upload_dir .  $to = iconv("UTF-8", "big5", $_FILES['goods_img']["name"][$i]);     
	 
	  if(!is_dir($upload_dir))mkdir ("upload/",0644); */

	  
	  
	  /****************使用圖片壓縮lib****************/
	 /* $filename=(_UPLOADPIC($_FILES["upfile"],$maxsize=1000000,$upload_file,$newname='date'));  
     $show_pic_scal=show_pic_scal(230, 230, $filename);  
     resize($filename,$show_pic_scal[0],$show_pic_scal[1]); 
	  
     //������������������������������������������������������
     if (!move_uploaded_file($_FILES['goods_img']["tmp_name"][$i], $upload_file))die( 'upload errer');
	

	}*/
	
	//圖片名稱寫入資料庫
	/*if(!isset($_FILES['upfile']['name']) || $_FILES['upfile']['name']==NULL)
	{$f="無圖片";}else{$f=$_FILES['upfile']['name'];}
	
	
	
	
  $insertSQL = sprintf("INSERT INTO `group` (gName, gIntroduction, gImages, cId, gmId) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['gName'], "text"),
                       GetSQLValueString($_POST['gIntro'], "text"),
					   GetSQLValueString($f, "text"),
                       GetSQLValueString($_POST['type'], "int"),					   
                       GetSQLValueString($_POST['gmId'], "int"));

  mysql_select_db($database_goldenbirdConn1, $goldenbirdConn1);
  $Result1 = mysql_query($insertSQL, $goldenbirdConn1) or die(mysql_error());

  $insertGoTo = "adminindex.php?gmId=" . $_SESSION['gmId'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  
  $i++;
	}//while-end
  header(sprintf("Location: %s", $editFormAction));
}*/

?>
<!doctype html>
<html><head>
<meta charset="utf-8">
<title>產品詳細內容</title>

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
<style type="text/css">
.bodyright { float:right; width:772px; margin-right:0px; padding:0; margin-top:5px; height:auto}
.bodyright_content { }
.products { padding:0px; margin:8px;  background:url('img2') repeat #ffffff; border:#44B6DF 1px solid; margin-bottom:5px; height:auto; overflow:hidden}
.products_left { float:left; margin:5px 15px 5px 5px; padding:10px;}
.products_left .bigpic { margin-bottom:5px;border:#CCCCCC 1px solid; padding-top:5px; background:#fff}
.products_left .bigpic a{ padding:5px;}
.products_left .smallpic { margin-top:10px; background:#fff}
.products_left .smallpic a{  }
.products_right { float:left; padding:5px; width:auto; overflow:hidden}
.products_right_content { margin-bottom:5px;}
.products_right_content .title{ font-size:14px; font-weight:bold; line-height:25px; margin:0}
.products_right_content p{ height:20px; line-height:20px; text-align:left; margin:0;}

.pic_list{	clear:both;margin:8px;
	/*padding:0px;   background:url('img2') repeat #ffffff; border:#44B6DF 1px solid; margin-bottom:5px; height:auto; overflow:hidden*/
}
.pic_list .small_pic{
	 width: 100px;
	 height:100px;
	 border:0;	 
	 margin-top: 10px;
	 margin-left: 10px;
}
</style>

</head>
<body>

    <?php require("_top.php"); ?>
    
    
    
    <!-- main coding-->
    <div id="right">
    
        
<h1><span class="action-span"><a href="edit.php?id=<?php echo $colname_prdu_one;?>&data=P">編輯</a></span>
<span class="action-span"><a href="<?php echo $_SERVER['HTTP_REFERER'];?>">回上一頁</a></span>網店管理 &gt;&gt; 商品詳細資料</h1>

<div id=tabbar-div>
    <P> <span class="tab-back" id="float-tab"><a href="prdu_manage.php" style="color:#FFF;">商品管理</a></span><span class="tab-front" id="LR-tab">商品詳細資料</span> </P>
  </div>

  

<div class="products" >
		<div class="products_left" >
			<div class="bigpic" style="position: absolute;">
            
        <?php if($totalRows_prdu_onepic!=0){ ?>
            <a href="upload/<?php echo $row_prdu_onepic['iName'];?>" target="_blank"><img src="upload/<?php echo $row_prdu_onepic['iName'];?>" width="200" height="200" border="0"></a>
        <?php }else{ ?>
        	<img src="upload/nofind.jpg" width="200" height="200" border="0">
        <?php } ?>
            <!--<div class="products_watermark"></div>-->
            </div>
			<div class="smallpic" style="margin-top:230px;">
			<!--商品相册部分开始--><div style="width:210px; height:0px; font-size:0px; line-height:0px;"></div>
				
			<!--商品相册部分结束-->
			</div>
		</div>
        
        
        <div style="float: right; margin:15px;"><a href="pdfoutput.php?id=<?php echo $row_prdu_one['pId'];?>" target="_brank"><img src="images/pdf2.png" width="48" height="24" border="0" alt="PDF" title="PDF產品手冊"/></a></div>
        
		<div class="products_right">
			<div class="products_right_content"><div class="title"><h3><?php echo $row_prdu_one['pName'];?></h3></div></div>
            <div class="products_right_content"><p>英文名稱：<?php echo $row_prdu_one['pName2'];?></p><p>商品編號： <?php echo $row_prdu_one['pNo']; ?></p></div>
                
			<div class="products_right_content"><p>商品分類：<?php echo $row_prdu_one['cName'];?> > <?php echo $row_prdu_one_cid2['cName'];?></p>
                <p>材質： <?php echo $row_prdu_one['pMaterial'];?></p>
                <p>尺寸： <?php echo $row_prdu_one['pSize'];?></p>
                <p>厚度： <?php echo $row_prdu_one['pThickness'];?></p>
                <p>顏色： <?php echo $row_prdu_one['pColor'];?></p>
            </div>
			<div class="products_right_content"><p>市場價格：<strike>￥0.00元</strike></p>
            	<p><font color="#FF0000">單價： <?php echo $row_prdu_one['pPrice']; ?>元</font></p>
			</div>
            <hr>
            <div class="products_right_content">
            <?php
	$costof	= ( $row_prdu_one['pSheetsfee']+$row_prdu_one['pMaterialfee']+$row_prdu_one['pPrintingfee']+$row_prdu_one['pProcessfee'] )*( 1+$row_prdu_one['pLoss']*0.01 )+
				( $row_prdu_one['pPackaging']+$row_prdu_one['pFreight'] )*
				( 1+$row_prdu_one['pProfits']*0.01 )*( 1+$row_prdu_one['pTaxesfee']*0.01 )
			?>
                <br>
                <p>片材費： <?php echo $row_prdu_one['pSheetsfee'];?> 元</p>
                <p>鋪料費： <?php echo $row_prdu_one['pMaterialfee'];?> 元</p>
                <p>印刷費： <?php echo $row_prdu_one['pPrintingfee'];?> 元</p>
                <p>加工費： <?php echo $row_prdu_one['pProcessfee'];?> 元</p>
                <p>損耗: <?php echo $row_prdu_one['pLoss'];?> %</p>
                
                <p>包裝費： <?php echo $row_prdu_one['pPackaging'];?> 元</p>
                <p>運費： <?php echo $row_prdu_one['pFreight'];?> 元</p>
                <p>利潤： <?php echo $row_prdu_one['pProfits'];?> %</p>
                <p>稅費： <?php echo $row_prdu_one['pTaxesfee'];?> %</p>
                <b><p>成本： <u><?php echo $costof;?></u> 元</p></b>
                <br>
                <p>紙箱尺寸: <?php echo $row_prdu_one['pCartonsize'];?></p>
                <p>裝箱數量: <?php echo $row_prdu_one['pPcs'];?></p>
                <p>裝箱重量: <?php echo $row_prdu_one['pKg'];?> KG</p>
            </div>
            
            
            <td align="center" valign="middle"></td>
      <td align="center" valign="middle"><?php echo $row_show_product['pPrice']; ?></td>
      <td align="center" valign="middle"></td>
      <td align="center" valign="middle"><?php echo $row_show_product['pSize']; ?></td>
      <!--<td align="center" valign="middle"><?php //echo $row_show_product['pCartonsize']; ?></td>-->
      <td align="center" valign="middle"><?php echo $row_show_product['pPcs']; ?></td>
      <td align="center" valign="middle"><?php echo $row_show_product['pKg']; ?></td>
			<!--商品属性开始-->
			<!--商品属性结束-->
			
		  <!--<div class="products_right_content">
			  
		  <input name="peisongfei" type="hidden" value="0" />
		  </div>
			<div class="products_right_content">购买数量：
			  <input name="pro_num" type="text" onKeyUp="this.value=this.value.replace(/[^\d]/g,'');" value="1" size="6" maxlength="5" />
			</div>
			<div class="products_right_content"><input name="go" value="购买" type="button" onclick="javascript:addtocar(30)" class="btn" id="buybtn" /> <input name="collect" type="button" value="收藏" onclick="collect(30)"  class="btn" id="favbtn"/></div>
		</div>
		<div class="clear"></div>-->
	</div>  
    
    
    <div class="pic_list">
     商品圖片 &gt;&gt;
    <hr>
    	<div>
    <?php	do{ ?>  
	<?php //echo $row_prdu_onepic['iId'];
		if($totalRows_prdu_onepic==0)break;
	?>  
    <a href="upload/<?php echo $row_prdu_onepic['iName'];?>">
    <img src="upload/<?php echo $row_prdu_onepic['iName'];?>" class="small_pic">
    </a>
    <?php }while($row_prdu_onepic = mysql_fetch_assoc($prdu_onepic));   ?>
    	&nbsp;</div>
	
    <!--<img src="upload/<?php echo '';?>" class="small_pic">
    <img src="upload/<?php echo '';?>" class="small_pic">
    <img src="upload/<?php echo '';?>" class="small_pic">
    <img src="upload/<?php echo '';?>" class="small_pic">
    <img src="upload/<?php echo '';?>" class="small_pic">
    <img src="upload/<?php echo '';?>" class="small_pic">
    <img src="upload/<?php echo '';?>" class="small_pic">-->
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
mysql_free_result($prdu_one);
?>
