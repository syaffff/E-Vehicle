<?php 
require_once('../Connections/dbConn.php'); 
?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}
// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../users/login2.php";
  if ($logoutGoTo) 
  {
  	echo "<script>
	alert('You Have Logout!')
	</script>";
	header("refresh:0 url=../users/login2.php");

    //header("Location: $logoutGoTo");
   
  }
}
?>
<?php
if (!isset($_SESSION)) 
{
	session_start();
}

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO vehicle (vehicleID, vehicleType, vehicleRoadTaxDate) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['vehicleID'], "text"),
                       GetSQLValueString($_POST['vehicleType'], "text"),
                       GetSQLValueString($_POST['vehicleRoadTaxDate'], "date"));
					   
  $insertSQL2 = sprintf("INSERT INTO owner (vehicleID, icNo, status, semester) VALUES (%s, %s, 'IN PROCESS', 'SEMESTER 2 2017/2018')",
  						GetSQLValueString($_POST['vehicleID'], "text"),
						GetSQLValueString($_SESSION['MM_Username'], "text"));

  mysql_select_db($database_dbConn, $dbConn);
  $Result1 = mysql_query($insertSQL, $dbConn) or die(mysql_error());
  $Result2 = mysql_query($insertSQL2, $dbConn) or die (mysql_error());
}



$colname_rsUser = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUser = $_SESSION['MM_Username'];
}
mysql_select_db($database_dbConn, $dbConn);
$query_rsUser = sprintf("SELECT * FROM `user` WHERE icNo = %s", GetSQLValueString($colname_rsUser, "text"));
$rsUser = mysql_query($query_rsUser, $dbConn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

mysql_select_db($database_dbConn, $dbConn);
$query_rsVehicle = "SELECT * FROM vehicle";
$rsVehicle = mysql_query($query_rsVehicle, $dbConn) or die(mysql_error());
$row_rsVehicle = mysql_fetch_assoc($rsVehicle);
$totalRows_rsVehicle = mysql_num_rows($rsVehicle);

$colname_rsOwner = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsOwner = $_SESSION['MM_Username'];
}
mysql_select_db($database_dbConn, $dbConn);
$query_rsOwner = sprintf("SELECT * FROM owner WHERE icNo = %s ORDER BY 
								case status
									when 'IN PROCESS' then 1
									when 'ACTIVE' then 2
									when 'INACTIVE' then 3
									else 4
								end", GetSQLValueString($colname_rsOwner, "text"));
$rsOwner = mysql_query($query_rsOwner, $dbConn) or die(mysql_error());
$row_rsOwner = mysql_fetch_assoc($rsOwner);
$totalRows_rsOwner = mysql_num_rows($rsOwner);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="../3372/styles.css" type="text/css" />
<style>
table {
    border-collapse: collapse;
    background-color: #ffffff;
    opacity: 0.7;
}

table, td, th {
    border: 3px solid black;
    align-items: center;
}
th, td {
    padding: 15px;
    text-align: center;
    color: black;
}
th {
    
    color: white;
}
td:hover{background-color:#4CAF50}


.search_form{
  text-align: center;
  margin: auto;
    width: 100%;
    background-color: #ffffff;
    opacity: 0.7;
    box-sizing: border-box;
    border: 2px solid green;
    border-radius: 4px;
  }
</style>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin-Search</title>

</head>

<body>
<section id="body" class="width">
	<aside id="sidebar" class="column-left">
	
		<header>
		<h1><a href="admin_home.php">UTeM</a></h1>
		<h2><?php echo $row_rsUser['userName'] ?> [Admin]</h2>
		</header>

			<nav id="mainnav">
  				<ul>
                  <li><a href="register_search_admin.php"> Sticker  Application </a></li>
                  <li><a href="admin_home.php"> Sticker Appllication List </a></li>
                  <li class="selected-item"><a href="admin_search.php"> Approved Sticker List </a></li>
                  <li><a href="summon.php"> Summon </a></li>
                  <li><a href="vehicle.php"> Vehicle </a></li>
                  <li><a href=""> Statistic </a></li>
                  <li><a href="<?php echo $logoutAction ?>">Log Out</a></li>
                	
           	  </ul>
			</nav>
			
	</aside>


<section id="content" class="column-center">
  
<br><h2 align="center">Please Enter Information Below.</h2>
<p>
<fieldset>
<div class="search_form">

<form method="GET" action="admin_search_conn.php">
<TR>
<TD>
<select name="searchtype" required="required" >
      <option value="">Select Options</option>
      <option value="icNo">Identity Card Number(NRIC)</option>
      <option value="userID">Matric Number</option>
      <option value="vehicleID">Vehicle Plate Number</option>
    </select>
</TD>
<TD>
<input type="text" name="search_data" style="width:150px;height:20px"  >
</TD>

<TD>
<input type="submit"  name="search" value="Search" style="height:30px;width:90px" >
</TD>

</TR>

</form>
</div>
</fieldset>
</p>


<p>
<fieldset>
<div class="update_form">
<?php

include('../Connections/dbcode.php');

$status="PASS";

$sql = mysqli_query($conn,'SELECT u.* , o.* FROM user u , owner o WHERE status = "'.$status.'" and o.icNo=u.icNo ORDER BY o.date_approve DESC');

if($sql->num_rows)

	{
		echo"<form  action='admin_app_approve.php' method='GET'>
				<table width='1000' height='209' border='1'>
  				<tr>
  				<th width='100' align='center'>IDENTITY CARD NUMBER(NRIC)</th>
    			<th width='100' align='center'>FULLNAME</th>
    			<th width='200' align='center'>ADDRESS</th>
    			<th width='100' align='center'>PHONE NUMBER</th>
				<th width='70' align='center'>VEHICLE PLATE NUMBER</th>
    			<th width='100' align='center'>STATUS</th>
    			<th width='100' align='center'>OPTION</th>
    			</tr>";	

	

		while($row = mysqli_fetch_array($sql))
		{
	
		$icno = $row['icNo'];
		$name = $row['userName'];
		$address = $row['userAddress'];
		$phone = $row['userTel'];
		$vehilceID = $row['vehicleID'];
		$status = $row['status'];
	
			echo"
    			<tr>
    			<td>$icno</td>
    			<td>$name</td>
   				<td>$address</td>
   				<td>$phone</td>
				<td>$vehilceID</td>
   				<td>$status</td>
   				
   				<td align='center'><a href='admin_approve.php?detail=$icno'>
				<img src='icon/detail.png' height= 27px; width= 27px;><br>Details</a>
				
				
				</td>
				</tr>";
				
		
		}
				"</table>
				
				</form>";

				$sql->close();	
	}

	

	/*else
	{

		echo "<script>
						alert('Record Not Found')
						
					</script>";
			header("refresh:0 url=deletestudent.php");
			

				$sql1->close();	

		} */
	
	


?>

</div>
</fieldset>
</p>
<article class="expanded">
		</article>

	</section>

	</section>

</body>
</html>