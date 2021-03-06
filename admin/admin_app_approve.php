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

th, td {
    padding: 15px;
    text-align: center;
    color: black;
}
th {
    
    color: white;
}

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
<title>Admin-Approve</title>

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
                	<li class="selected-item"><a href="admin_home.php"> Sticker Appllication List </a></li>
               		<li><a href="admin_search.php"> Approved Sticker List </a></li>
                	<li><a href="summon.php"> Summon </a></li>
                  	<li><a href="vehicle.php"> Vehicle </a></li>
                  	<li><a href="statistic.php"> Statistic </a></li>
                	<li><a href="<?php echo $logoutAction ?>">Log Out</a></li>
           	  </ul>
			</nav>
			
	</aside>



<section id="content" class="column-center">	

<p>
<fieldset>
<br><h2 align="center">Please Enter Information Below.</h>

<div class="search_form">
<form method="GET" action="admin_app_approve.php">
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


<section id='content' class='column-right'>
<p>
<fieldset>


<div class="update_form">
<?php
include('../Connections/dbcode.php');
include 'vendor/autoload.php';
	use Endroid\QrCode\QrCode;


if(isset($_GET['detail']))
    {
       $icNo=$_GET['detail'];


            $sql1 = mysqli_query($conn,'SELECT * FROM user WHERE icNo = "'.$icNo.'"');
			
			//$sql = mysqli_query($conn,'SELECT u.* , o.* FROM user u , owner o WHERE icNo = "'.$icNo.'"');

            if($sql1->num_rows)
                 {
               
		             while($row = mysqli_fetch_array($sql1))
		                {
		             	
		                    $icno = $row['icNo'];
							$matric = $row['userID'];
		                    $name = $row['userName'];
							$residential = $row['userResidential'];
							$faculty= $row['userFaculty'];
							$eduLevel= $row['userEduLevel'];
		                    $address = $row['userAddress'];
		                    $phone = $row['userTel'];
							$licenseNo = $row['userLicenseNo'];
							$licenseDate = $row['userLicenseValidDate'];
							$position = $row['userPosition'];
							$icFile = $row['icFile'];

							
	
                			echo"
								
                     						                		
	    						<article>
									  <p>&nbsp;</p>
									  <fieldset>
							          <legend>Users Details</legend>
							          
							          <form action='' method='post'>
							            <table align='center'>
							              <tr valign='baseline'>
							                <td nowrap='nowrap' align='right'>Identification Card No:</td>
							                <td>  $icno </td>
							              </tr>
							              <tr valign='baseline'>
							                <td nowrap='nowrap' align='right'>ID:</td>
							                <td> $matric </td>
							              </tr>
							                <tr valign='baseline'>
							                <td nowrap='nowrap' align='right'>Name:</td>
							                <td> $name </td>
							              </tr>
							                <tr valign='baseline'>
							                <td nowrap='nowrap' align='right'>Residential:</td>
							                <td> $residential </td>
							              </tr>
							              <tr valign='baseline'>
							                <td nowrap='nowrap' align='right'>Faculty:</td>
							                <td> $faculty </td>
							              </tr>
							              <tr valign='baseline'>
							                <td nowrap='nowrap' align='right'>Edu Level:</td>
							                <td> $eduLevel </td>
							              </tr>
							              <tr valign='baseline'>
							                <td align='right' valign='middle' nowrap='nowrap'>Address:</td>
							                <td> $address </td>
							              </tr>
							              <tr valign='baseline'>
							                <td nowrap='nowrap' align='right'>Telephone No:</td>
							                <td> $phone </td>
							              </tr>
							              <tr valign='baseline'>
							                <td nowrap='nowrap' align='right'>License No:</td>
							                <td> $licenseNo </td>
							              </tr>
							              <tr valign='baseline'>
							              	<td nowrap='nowrap' align='right'>License Valid Date:</td>
							                <td>$licenseDate</td>
							              </tr>
							              <tr valign='baseline'>
							                <td nowrap='nowrap' align='right'>Position:</td>
							                <td> $position </td>
							              </tr>

							              <tr valign='baseline'>
							                <td nowrap='nowrap' align='right'>Identification Card Picture:</td>
							               <td> <a href='getImage.php?icNo=$icno'><img src='icon/eye.png' width='40' height='40' /></td>
							              </tr>

							              <tr valign='baseline'>
							                <td nowrap='nowrap' align='right'> License Card Picture:</td>
							                <td>  <a href='getImage2.php?icNo=$icno'><img src='icon/eye.png' width='40' height='40' /></td>
							              </tr>

							              <tr valign='baseline'>
							                <td nowrap='nowrap' align='right'>Matric Card Or Staff Card Picture:</td>
							                <td> <a href='getImage3.php?icNo=$icno'><img src='icon/eye.png' width='40' height='40' /> </td>
							              </tr>
							              <tr valign='baseline'>
							                <td nowrap='nowrap' align='right'>&nbsp;</td>
							                <td><a href='admin_home.php'>
											<img src='icon/ok.png' height= 27px; width= 27px;><br>Ok</a></td>
							              </tr>
							            </table>
							            
							          </form>
							          </fieldset>

							          </article> 
							           ";	
                		   }
                				
                    }
    			}
    
    
			else if(isset($_GET['approve']))
				    {             
				        $icNo=$_GET['approve'];
						$vehicleID = $_GET['vehicleID'];
				        $status="PASS";
					
					
				        
				        $sql ="UPDATE owner SET status='".$status."', date_approve=NOW() WHERE icNo='".$icNo."' ";
				        
				        $result = $conn->query($sql);

									
									if ($result)
									{

										/*echo "
												

												<div class='panel-heading' style='text-align:center;'>
												<h1>The Generated QRCode For Sticker</h1>
												</div>
												<hr>
												<div id='qrbox' style='text-align:center;'>
												<img src='generate_qrcode.php?text=$vehicleID' alt=''>
												<br>

												<a href='qrcode_pdf.php?vehicleID=$vehicleID'>
         										<img src='icon/print.png' height= 27px; width= 27px;><br>PRINT QRCODE</a>
												</div>
												

												";
												*/
										echo "
										<script>
										alert('Application Has Been Approve')
										</script>";

										header("refresh:0 url=admin_home.php");
									
									}
					
    						}


else if(isset($_GET['search']))
  {
	  	$searchtype = $_GET['searchtype'];
	 	$searchdata = $_GET['search_data'];
		

		$status="IN PROCESS";

		if($searchtype == 'icNo')
		{
			$icNo = mysqli_query($conn,'SELECT u.* , o.* FROM user u , owner o WHERE u.icNo LIKE "%'.$searchdata.'%" and status = "'.$status.'" 
			and o.icNo=u.icNo ');


			if($icNo->num_rows)
			{
			echo"<form  action='deletestudent.php' method='GET'>
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
			while($row = mysqli_fetch_array($icNo))
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
	   				
	   				
					<td align='center'><a href='admin_app_approve.php?detail=$icno'>
					<img src='icon/detail.png' height= 27px; width= 27px;><br>Details</a>	

					<br><br>
					<a href='admin_app_approve.php?approve=$icno&vehicleID=$vehilceID'>
					<img src='icon/approve.png' height= 27px; width= 27px;><br>Approve</a>			
					</td>
					</tr>";
				
				}
					"</table>
					
					</form>";

					$icNo->close();
			}

		}

		else if($searchtype == 'userID')
		{
			$userID = mysqli_query($conn,'SELECT u.* , o.*  FROM user u , owner o WHERE u.userID like "%'.$searchdata.'%"  
			and status = "'.$status.'" and o.icNo=u.icNo');

			if($userID->num_rows)
			{
			echo"<form  action='deletestudent.php' method='GET'>
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
			while($row = mysqli_fetch_array($userID))
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
	   				
	   				
					<td align='center'><a href='admin_app_approve.php?detail=$icno'>
					<img src='icon/detail.png' height= 27px; width= 27px;><br>Details</a>	

					<br><br>
					<a href='admin_app_approve.php?approve=$icno&vehicleID=$vehilceID'>
					<img src='icon/approve.png' height= 27px; width= 27px;><br>Approve</a>					
					</td>
					</tr>";
				
				}
					"</table>
					
					</form>";	

					$userID->close();
			}

		}

		else if($searchtype == 'vehicleID')
		{
			$vehicleID = mysqli_query($conn,'SELECT u.* , o.*  FROM user u , owner o WHERE o.vehicleID like "%'.$searchdata.'%" and status = "'.$status.'" and o.icNo=u.icNo ');

			if($vehicleID ->num_rows)
			{
			echo"<form  action='deletestudent.php' method='GET'>
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
				while($row = mysqli_fetch_array($vehicleID ))
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
	   				
	   				
					<td align='center'><a href='admin_app_approve.php?detail=$icno'>
					<img src='icon/detail.png' height= 27px; width= 27px;><br>Details</a>	

					<br><br>
					<a href='admin_app_approve.php?approve=$icno&vehicleID=$vehilceID'>
					<img src='icon/approve.png' height= 27px; width= 27px;><br>Approve</a>				
					</td>
					</tr>";
				
				}
					"</table>
					
					</form>";

					$vehicleID->close();
			}
		}
	  }

else
	{
		
		echo "<script>
						alert('Record Not Found')
						
					</script>";
			header("refresh:0 url=admin_home.php");
	}
    
								/*
								<br><br><h2>The Details</h2><br>
								
								<ul align='center' style='list-style-type:disc'>
  									<li>IDENTITY CARD NUMBER(NRIC): $icno</li>
  									<li>MATRIC CARD: $matric</li>
  									<li>FULLNAME: $name</li>
									<li>RESIDENTIAL: $residential</li>
									<li>FACULTY: $faculty</li>
									<li>EDUCATION LEVEL: $eduLevel</li>
									<li>ADDRESS: $address</li>
									<li>PHONE NUMBER: $phone</li>
									<li>LICENSE NUMBER: $licenseNo</li>
									<li>LICENCE VALID NUMBER: $licenseDate</li>
									<li>POSITION:$position</li>
									<a href='admin_home.php'>
									<img src='icon/ok.png' height= 27px; width= 27px;><br>Ok</a>
									
									</ul>

									*/
?>

</div>

</fieldset>
</p>

<footer class="clear">
            
		<p>&nbsp;</p>
			</footer>
		
<article class="expanded">
		</article>

	</section>
<div class="clear"></div>
	</section>

</body>
</html>