<?php 
require_once('../Connections/dbConn.php'); 
?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

$for = "";
$sem = "";
$who = "";
$faculty = "";	
$caption = "";
$subCaption = "";

if(isset($_POST['for']) && isset($_POST['sem']) && isset($_POST['who']) && isset($_POST['faculty']))
{
	$for = $_POST['for'];
	$sem = $_POST['sem'];
	$who = $_POST['who'];
	$faculty = $_POST['faculty'];
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


$colname_rsUser = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUser = $_SESSION['MM_Username'];
}
mysql_select_db($database_dbConn, $dbConn);
$query_rsUser = sprintf("SELECT * FROM `user` WHERE icNo = %s", GetSQLValueString($colname_rsUser, "text"));
$rsUser = mysql_query($query_rsUser, $dbConn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

$colname_rsOwner = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsOwner = $_SESSION['MM_Username'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8; " />
<title>Untitled Document</title>
<link rel="stylesheet" href="../3372/styles.css" type="text/css" />
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<script type="text/javascript" src="../js/fusioncharts.js"></script>
	
	<style>
	
	.code-block-holder pre {
		  max-height: 188px;  
		  min-height: 188px; 
		  overflow: auto;
		  border: 1px solid #ccc;
		  border-radius: 5px;
	}
	.tab-btn-holder {
		width: 50%;
		margin: 20px 0 0;
		border-bottom: 1px solid #dfe3e4;
		min-height: 30px;
	}
	.tab-btn-holder a {
		background-color: #fff;
		font-size: 14px;
		text-transform: uppercase;
		color: #006bb8;
		text-decoration: none;
		display: inline-block;
		*zoom:1; *display:inline;
	}
	.tab-btn-holder a.active {
		color: #858585;
		padding: 9px 10px 8px;
		border: 1px solid #dfe3e4;
		border-bottom: 1px solid #fff;
		margin-bottom: -1px;
		border-top-left-radius: 5px;
		border-top-right-radius: 5px;
		position: relative;
		z-index: 300;
	}
	.graph
	{
		margin-left:0px;
	}
	</style>
<body>
<section id="body" class="width">
	<aside id="sidebar" class="column-left">
	
		<header>
		<h1><a href="admin_home.php">UTeM</a></h1>
		<h2><?php echo $row_rsUser['userName'] ?> [Admin]</h2>
		</header>

			<nav id="mainnav">
  				<ul>
                	<li ><a href="admin_home.php">Sticker</a></li>
               		<li><a href="register_search_admin.php">Register</a></li>
                	<li><a href="admin_search.php">Approve</a></li>
                	<li><a href="summon.php"> Summon </a></li>
                    <li><a href="vehicle.php"> Vehicle </a></li>
                    <li class="selected-item"><a href="statistic.php"> Statistic </a></li>
                	<li><a href="<?php echo $logoutAction ?>">Log Out</a></li>
           	  </ul>
			</nav>
			
	</aside>
    
<section id="content" class="column-right">
 <article>
		  <div class="article-info"></div>
		  <div class="graph">
<?php


include("fusioncharts.php");
require("../Connections/dbConn.php");

$con=mysqli_connect ('localhost','root','','utemsticker');

	echo "For : ". $for;
	echo "Semester : ". $sem;
	echo "Who      : ". $who;
	echo "Faculty  : ". $faculty;
	echo "<p>";
	echo "<p>";
	
	$sql = NULL;
	
	if ($for == "list")
	{
		if ($who == "Student" || $who == "Lecturer")
		{
			$sql = "SELECT status, COUNT(icNo) FROM user JOIN owner USING (icNo) WHERE userPosition='$who' AND userFaculty='$faculty' AND semester='$sem' GROUP BY status";
			
			$caption = "Graph of Approval List of " . $who . " in " . $faculty . " (" . $sem . ")";
			
		}
		else if ($who == "Contractor")
		{
			$sql = "SELECT status, COUNT(icNo) FROM user JOIN owner USING (icNo) WHERE userPosition='$who' AND semester='$sem' GROUP BY status";
			
		} else if ($who == "All")
		{
			$sql = "SELECT status, COUNT(icNo) FROM user JOIN owner USING (icNo) WHERE semester='$sem' GROUP BY status";
			
		}
		
	}
	
	else if ($for == "type")
	{
		if ($who == "Student" || $who == "Lecturer")
		{
			$sql = "SELECT vehicleType, COUNT(icNo) FROM user JOIN owner USING (icNo) JOIN vehicle USING (vehicleID) WHERE userPosition='$who' AND userFaculty='$faculty' AND semester='$sem' GROUP BY vehicleType";
			
		}
		else if ($for == "Contractor")
		{
			$sql = "SELECT vehicleType, COUNT(icNo) FROM user JOIN owner USING (icNo) JOIN vehicle USING (vehicleID) WHERE userPosition='$who' AND semester='$sem' GROUP BY vehicleType";
			
		} else if ($for == "All")
		{
			$sql = "SELECT vehicleType, COUNT(icNo) FROM user JOIN owner USING (icNo) JOIN vehicle USING (vehicleID) WHERE semester='$sem' GROUP BY vehicleType";
			
		}
	}
	
	if (!$sql)
	{
		echo "<script type='text/javascript'>alert('Please select an option!')</script>";
	}
	else
	{
		$result=mysqli_query($con, $sql);
	
		if ($for == "list")
		{
			$arrData = array(
					"chart" => array(
					"caption" => $caption,
					"subCaption" => "",
					"xAxisName" => "",
					"yAxisName" => "",				
					"showValues" => "0",
					"theme" => "zune"
					)
			);
		
			$arrData["data"] = array();
				
			while($row = mysqli_fetch_array($result)) 
			{
				array_push($arrData["data"], array(
						"label" => $row["status"], //palsi x
						"value" => $row["COUNT(icNo)"] // paksi y
						)
					);
			 }
		} else if ($for == "type")
		{
			$arrData = array(
					"chart" => array(
					"caption" => "Type of Vehicles",
					"showValues" => "0",
					"theme" => "zune"
					)
			);
		
			$arrData["data"] = array();
				
			while($row = mysqli_fetch_array($result)) 
			{
				array_push($arrData["data"], array(
						"label" => $row["type"], //palsi x
						"value" => $row["COUNT(icNo)"] // paksi y
						)
					);
			 }
		}
	
		 $jsonEncodedData = json_encode($arrData);
	
		 $columnChart = new FusionCharts("column2D", "myFirstChart" , 1000, 300, "chart-1", "json", $jsonEncodedData);
	
		  $columnChart->render();
	}

?>
<div id="chart-1"><!-- Fusion Charts will render here--></div>
</div>
</article>

 <p>&nbsp;</p>
 <p>&nbsp;</p>
 <p>&nbsp;</p>
 
 <footer class="clear">
 <p>Pejabat Keselamatan | Universiti Teknikal Malaysia Melaka, Hang Tuah Jaya, 76100 Durian Tunggal, Melaka, Malaysia. 
Phone: +606-3316371   |   Fax: +606-3316344   |  hdajssadn dosjdsjdnaus dnxjskn nxosandjksanxa ndjsknxjknas dxasndjksandjkasndjsdnksndjksdnsjkndjsndsjandjadnjasdnajdnjakdnasjdnadajndasjkdasdnasjdnskjdnjk </p>
 </footer>
 </section>
 <div class="clear"></div>
 </section>
<script>
function myFunction(val) 
{
    if (val === "Contractor" || val === "All") 
	{
        document.getElementById("faculty").disabled = true;
    } else
	{
		document.getElementById("faculty").disabled = false;
	}
}

function checkOption() {
	var x = document.getElementById("who");
	var input = document.getElementById("faculty");
	input.disabled = x.value == "All" || "Contractor";
}
</script>

</body>
</html>