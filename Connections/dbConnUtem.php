<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_dbConnUtem = "localhost";
$database_dbConnUtem = "maindb_utem";
$username_dbConnUtem = "root";
$password_dbConnUtem = "";
$dbConnUtem = mysql_pconnect($hostname_dbConnUtem, $username_dbConnUtem, $password_dbConnUtem) or trigger_error(mysql_error(),E_USER_ERROR); 
?>