<?php
$hostname_dbConnUtem = "localhost";
$database_dbConnUtem = "maindb_utem";
$username_dbConnUtem = "root";
$password_dbConnUtem = "";

$dbh1 = mysql_connect($hostname_dbConnUtem, $username_dbConnUtem, $password_dbConnUtem); 
$dbh2 = mysql_connect($hostname_dbConnUtem, $username_dbConnUtem, $password_dbConnUtem, true); 



mysql_select_db('maindb_utem', $dbh1);
mysql_select_db('utemsticker', $dbh2);


?>