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

$colname_Recordset1 = "-1";
if (isset($_GET['cId']) && $_GET['cId']!=0) {
  $colname_Recordset1 = $_GET['cId'];
}
mysql_select_db($database_prdu_manage, $prdu_manage);
$query_Recordset1 = sprintf("SELECT * FROM class WHERE `cPre`=%s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $prdu_manage) or die(mysql_error());
//$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

if(1/*$totalRows_Recordset1!=0*/){?>

        <select name="cat_id2" onchange="" value='1'>
          <!--<option value='99'>请选择...</option>-->
		  <?php while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)) { ?>
          <option value='<?php echo $row_Recordset1['cId'] ?>'>&nbsp;<?php echo $row_Recordset1['cName'] ?></option>
        <?php }  ?>
        </select>
<?php }?>

<?php
mysql_free_result($Recordset1);
?>
