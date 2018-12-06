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


$mId=($_GET['mId']);
$id=($_GET['id']);
$data=($_GET['data']);

if($data=='M'){
	if ((isset($id)) && ($id != '')) {
	  $deleteSQL = sprintf("DELETE FROM member WHERE mId=%s",
						   GetSQLValueString($_GET['id'], "int"));
	
	  mysql_select_db($database_prdu_manage, $prdu_manage);
	  $Result1 = mysql_query($deleteSQL, $prdu_manage) or die(mysql_error());
	
	  $deleteGoTo = "member.php";
	  /*if (isset($_SERVER['QUERY_STRING'])) {
		$deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
		$deleteGoTo .= $_SERVER['QUERY_STRING'];
	  }*/
	  header(sprintf("Location: %s", $deleteGoTo));
	}
}else if($data=='P'){
	if ((isset($id)) && ($id != '')) {
		
		
		$deleteSQL = sprintf("SELECT iName FROM images WHERE pId IN (%s) ",$_GET['id']);
						   //GetSQLValueString($_GET['id'], "int"));	
	  mysql_select_db($database_prdu_manage, $prdu_manage);
	  $Result1 = mysql_query($deleteSQL, $prdu_manage) or die(mysql_error());
	  
	  while($Result2 = mysql_fetch_assoc($Result1)){
	  		unlink("upload/". $Result2['iName'] );
	  }
	  
	  
		
	  $deleteSQL = sprintf("DELETE FROM `product`  WHERE pId IN (%s) ",$_GET['id']);
						   //GetSQLValueString($_GET['id'], "int"));	
	  mysql_select_db($database_prdu_manage, $prdu_manage);
	  $Result1 = mysql_query($deleteSQL, $prdu_manage) or die(mysql_error());
	  
		  $deleteSQL = sprintf("DELETE FROM `images` WHERE pId IN (%s) ",$_GET['id']);
						   //GetSQLValueString($_GET['id'], "int"));		
		  mysql_select_db($database_prdu_manage, $prdu_manage);
		  $Result1 = mysql_query($deleteSQL, $prdu_manage) or die(mysql_error());
	  
	
	  $deleteGoTo = "prdu_manage.php";
	  /*if (isset($_SERVER['QUERY_STRING'])) {
		$deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
		$deleteGoTo .= $_SERVER['QUERY_STRING'];
	  }*/
	}	
	  
	  //header(sprintf("Location: %s", $deleteGoTo));
	  header(sprintf("Location: %s", $_SERVER['HTTP_REFERER']));
	
}else if($data=='C'){
	if ((isset($id)) && ($id != "")) {
		
	  $deleteSQL = sprintf("DELETE FROM class WHERE cId=%s",
						   GetSQLValueString($_GET['id'], "int"));
	
	  mysql_select_db($database_prdu_manage, $prdu_manage);
	  $Result1 = mysql_query($deleteSQL, $prdu_manage) or die(mysql_error());
	
	  $deleteGoTo = "prdu_class.php";
	  /*if (isset($_SERVER['QUERY_STRING'])) {
		$deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
		$deleteGoTo .= $_SERVER['QUERY_STRING'];
	  }*/
	  
	  //header(sprintf("Location: %s", $deleteGoTo));
	  
	  header(sprintf("Location: %s", $_SERVER['HTTP_REFERER']));
	}
}else if($data=='I'){
	if ((isset($id)) && ($id != '')) {
		
		
	  $deleteSQL = sprintf("SELECT iName FROM images WHERE iId=%s ",
						   GetSQLValueString($_GET['id'], "int"));	
	  mysql_select_db($database_prdu_manage, $prdu_manage);
	  $Result1 = mysql_query($deleteSQL, $prdu_manage) or die(mysql_error());
	  $Result2 = mysql_fetch_assoc($Result1);
	  unlink("upload/". iconv('UTF-8','gb2312',$Result2['iName']) );
	  
	  
		
	  $deleteSQL = sprintf("DELETE FROM images WHERE iId=%s",
						   GetSQLValueString($_GET['id'], "int"));
	
	//echo "?".$id."&".$data;
	  mysql_select_db($database_prdu_manage, $prdu_manage);
	  $Result1 = mysql_query($deleteSQL, $prdu_manage) or die(mysql_error());
	
	  /*$deleteGoTo = $_SERVER['PHP_SELF'];//"prdu_class.php";
	  if (isset($_SERVER['QUERY_STRING'])) {
		$deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
		$deleteGoTo .= $_SERVER['QUERY_STRING'];
	  }
	  header(sprintf("Location: %s", $deleteGoTo));*/
	  
	  
	  
	}
}else {
	header(sprintf("Location: %s", "logout.php"));
}


?>
