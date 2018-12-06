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

$colname_pdf1 = "-1";
if (isset($_GET['id']) && $_GET['id']!=NULL) {
  $colname_pdf1 = $_GET['id'];
  
mysql_select_db($database_prdu_manage, $prdu_manage);
$query_pdf1 = sprintf("SELECT * FROM product LEFT JOIN images ON product.pId=images.pId WHERE product.pId = %s", GetSQLValueString($colname_pdf1, "int"));
$pdf1 = mysql_query($query_pdf1, $prdu_manage) or die(mysql_error());
//$row_pdf1 = mysql_fetch_assoc($pdf1);
$totalRows_pdf1 = mysql_num_rows($pdf1);
}



$colname_pdf2 = "-1";
if (isset($_GET['q']) && $_GET['q']!=NULL) {
  $colname_pdf2 = $_GET['q'];
  $colname_pdf2 = str_replace("&rhh",'%',urldecode($colname_pdf2));
  $colname_pdf2 = str_replace("&slt","SELECT",$colname_pdf2);
  $colname_pdf2 = str_replace("&fm","FROM",$colname_pdf2);
  $colname_pdf2 = stripslashes($colname_pdf2);
  //die();
  
mysql_select_db($database_prdu_manage, $prdu_manage);
//$query_pdf1 = sprintf("%s ", GetSQLValueString($colname_pdf2, "text"));
$pdf1 = mysql_query($colname_pdf2, $prdu_manage) or die(mysql_error());
//$row_pdf1 = mysql_fetch_assoc($pdf1);
$totalRows_pdf1 = mysql_num_rows($pdf1);
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>無法載入</title>
<?
/*define('FPDF_FONTPATH','fpdf152/font/');
require('fpdf152/fpdf.php');
$pdf = new FPDF('P', 'mm', 'A4'); 

$pdf->Open();
$pdf->AddPage();
$pdf->SetFont('arial');
$pdf->Text(5,20,'test pdf');
$pdf->Image('upload/4.jpg', 5, 30, 0, 0);*/
//$pdf->Output('a.pdf', 'D');



//$pdf->Output();
?>
</head>
<body>
<?php
ob_start();
require_once('tcpdf/tcpdf.php');   
//实例化   
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);   
   
// 设置文档信息   
$pdf->SetCreator('Goldenbird');   
$pdf->SetAuthor('Goldenbird');   
$pdf->SetTitle('Welcome to Goldenbird.com!');   
$pdf->SetSubject('TCPDF Tutorial');   
$pdf->SetKeywords('TCPDF, PDF, PHP');   
   
//设置页眉和页脚信息   
$pdf->SetHeaderData('logo.png', 30, "陽光塑膠集團  SUNSHINE  PLASTIC  GROUP", 'www.sunshine-packing.com',    
      array(0,64,255), array(0,64,128));   
$pdf->setFooterData(array(0,64,0), array(0,64,128));   
   
// 设置页眉和页脚字体   
$pdf->setHeaderFont(Array('stsongstdlight', '', '10'));   
$pdf->setFooterFont(Array('helvetica', '', '8'));   
   
// 设置默认等宽字体   
$pdf->SetDefaultMonospacedFont('courier');   
   
// 设置间距   
$pdf->SetMargins(15, 27, 15);   
$pdf->SetHeaderMargin(5);   
$pdf->SetFooterMargin(10);   
   
// 设置分页   
$pdf->SetAutoPageBreak(TRUE, 25);   
   
// set image scale factor   
$pdf->setImageScale(1.25);   
   
// set default font subsetting mode   
$pdf->setFontSubsetting(true);   
   
//设置字体   
$pdf->SetFont('droidsansfallback', '', 12);   
   
$pdf->AddPage();   
   
//$str1 = 'welcome to Goldbird.com.tw';   


$x=0;   $y=0; 
while($row_pdf1 = mysql_fetch_assoc($pdf1)){	
	if($y==2){	$pdf->AddPage();	$y=0;			$x=0;	}		
	
		if($row_pdf1['iName']=="")$row_pdf1['iName']="nofind.jpg";
	
	$pdf->Image("upload/".$row_pdf1['iName'], 12+$x*140, 40+75*$y, 65, 55) or die('檔案大小數據太大 或是 使用中文檔名!! 導致無法載入PDF');
	
	//设置字体   
$pdf->SetFont('', '', 13); 
	$pdf->Text(80+$x*140, 48+75*$y, '名稱: '.$row_pdf1['pName']);// or die('數據太大!!無法載入PDF');	
	$pdf->Text(80+$x*140, 53+75*$y, 'Name: '.$row_pdf1['pName2']);// or die('數據太大!!無法載入PDF');
	
	//设置字体   
$pdf->SetFont('', '', 11); 	
	$pdf->Text(80+$x*140, 60+75*$y, '編號(Item no): '.$row_pdf1['pNo']);// or die('數據太大!!無法載入PDF');
	$pdf->Text(80+$x*140, 65+75*$y, '尺寸(Size): '.$row_pdf1['pSize']);
	$pdf->Text(80+$x*140, 70+75*$y, '單位(Unit): '."");
	$pdf->Text(80+$x*140, 75+75*$y, '單價(Price): '.$row_pdf1['pPrice']);
	$pdf->Text(80+$x*140, 80+75*$y, '紙箱尺寸(Carton Size): '.$row_pdf1['pCartonsize']);
	$pdf->Text(80+$x*140, 85+75*$y, '裝箱數量(Quantity): '.$row_pdf1['pPcs']);	
	
	
	$x+=1;
	if($x==2){		$y+=1;			$x=0;	}	
}


   
//$pdf->Write(0,$str1,'', 0, 'L', true, 0, false, false, 0);   
   
   ob_flush();
   
//输出PDF   
$pdf->Output('sunshine.pdf', 'I');   
?>
</body>
</html>
<?php
mysql_free_result($pdf1);
?>
