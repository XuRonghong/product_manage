<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_prdu_manage = "localhost";
$database_prdu_manage = "product_manage";
$username_prdu_manage = "root";
$password_prdu_manage = "123456";

$hostname_prdu_manage2 = "sql210.000space.com";
$database_prdu_manage = "space_12472168_product_manage";
$username_prdu_manage2 = "space_12472168";
$password_prdu_manage2 = "eivk88e3";

$hostname_prdu_manage3 = "182.18.8.72";
//$database_prdu_manage = "zjwdb_95289";
$username_prdu_manage3 = "zjwdb_95289";
$password_prdu_manage3 = "MUHZWROYP";

$prdu_manage = /* mysql_pconnect($hostname_prdu_manage, $username_prdu_manage, $password_prdu_manage) or  */
				mysql_pconnect($hostname_prdu_manage2, $username_prdu_manage2, $password_prdu_manage2) /* or 
				mysql_pconnect($hostname_prdu_manage3, $username_prdu_manage3, $password_prdu_manage3) */ or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_query("set names utf8");
?>