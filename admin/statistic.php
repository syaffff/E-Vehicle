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
<script type="text/javascript" src="js/fusioncharts.js"></script>
	
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

  					<li><a href="register_search_admin.php"> Sticker Application </a></li>
                	<li><a href="admin_home.php"> Sticker Appllication List </a></li>
               		<li><a href="admin_search.php"> Approved Sticker List </a></li>
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
          <fieldset>
          <legend>Report</legend>
          <br />
          <br />
          <br />
          
          <form id="form1" name="form1" method="post" action="">
  <table align="center">
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Report of :</td>
    <td align="left"><select name="for" id="for">
      <option selected="selected">Select</option>
      <option value="list">Approval List</option>
      <option value="type">Type of Vehicles</option>
    </select>
    </td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Semester  :</td>
    <td align="left"><select name="sem" id="sem">
      <option selected="selected">Select</option>
      <option value="Semester 1 2016/2017">Semester 1 2016/2017</option>
      <option value="Semester 2 2016/2017">Semester 2 2016/2017</option>
      <option value="Semester 1 2017/2018">Semester 1 2017/2018</option>
      <option value="Semester 2 2017/2018">Semester 2 2017/2018</option>
    </select>
    </td>
  </tr>
   <tr valign="baseline">
    <td nowrap="nowrap" align="right">Who  :</td>
    <td align="left"><select name="who" id="who" onChange="myFunction(this.value)">
      <option selected="selected">Select</option>
      <option value="Student">Student</option>
      <option value="Lecturer">Lecturer</option>
      <option value="Contractor">Contractor</option>
      <option value="All">All</option>
    </select>
    </td>
  </tr>
   <tr valign="baseline">
    <td nowrap="nowrap" align="right">Faculty  :</td>
    <td align="left"> <select name="faculty" id="faculty">
      <option selected="selected">Select</option>
      <option value="FTMK">FTMK</option>
      <option value="FKP">FKP</option>
      <option value="FKEKK">FKEKK</option>
      <option value="FKE">FKE</option>
      <option value="FTK">FTK</option>
      <option value="FKM">FKM</option>
      <option value="FPTT">FPTT</option>
      <option value="All">All</option>
    </select>
    </td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right"></td>
    <td align="left"><input class="formbutton" type="submit" name="submit" id="submit" value="Submit" />
    </td>
  </tr>
  </table>
  <?php

if (isset($_POST["submit"]))
{
	$for = NULL;
	$sem = NULL;
	$who = NULL;
	$faculty = NULL;	
	$caption = "";
	$subCaption = "";
	
	if($_POST['for'] == "Select")
		echo "<script type='text/javascript'>alert('Select the result for what!!')</script>";
	else if(isset($_POST['for']))
		$for = $_POST['for'];
		
	if($_POST['sem'] == "Select")
		echo "<script type='text/javascript'>alert('Select the semester!!')</script>";
	else if(isset($_POST['sem']))
		$sem = $_POST['sem'];
		
	if($_POST['who'] == "Select")
		$who = "All";
	else if(isset($_POST['who']))
		$who = $_POST['who'];
	
	if($_POST['faculty'] == "Select")
		$faculty = "All";
	else if(isset($_POST['faculty']))
		$faculty = $_POST['faculty'];
	
	/*
	if(isset($_POST['for']) && isset($_POST['sem']) && isset($_POST['who']) && isset($_POST['faculty']))
	{
		$for = $_POST['for'];
		$sem = $_POST['sem'];
		$who = $_POST['who'];
		$faculty = $_POST['faculty'];
	} */
	
	echo "For      : ". $for; echo "<br>";
	echo "Semester : ". $sem; echo "<br>";
	echo "Who      : ". $who; echo "<br>";
	echo "Faculty  : ". $faculty; echo "<br>";
	echo "<br>";
	echo "<br>";
	
	include("fusioncharts.php");
	require("../Connections/dbConn.php");
	
	$con=mysqli_connect ('localhost','root','','utemsticker');
	
	$sql = NULL;
	
	if ($for == "list")
	{
		if ($who == "Student" || $who == "Lecturer")
		{
			$sql = "SELECT status, COUNT(icNo) FROM user JOIN owner USING (icNo) WHERE userPosition='$who' AND userFaculty='$faculty' AND semester='$sem' GROUP BY status";
			
			$caption = "Graph of Approval List of " . $who . " in " . $faculty;
			
		}
		else if ($who == "Contractor")
		{
			$sql = "SELECT status, COUNT(icNo) FROM user JOIN owner USING (icNo) WHERE userPosition='$who' AND semester='$sem' GROUP BY status";
			
			$caption = "Graph of Approval List of " . $who;
			
		} else if ($who == "All")
		{
			$sql = "SELECT status, COUNT(icNo) FROM user JOIN owner USING (icNo) WHERE semester='$sem' GROUP BY status";
			
			$caption = "Graph of Total Approval List ";
			
		}
		
	}
	
	else if ($for == "type")
	{
		if ($who == "Student" || $who == "Lecturer")
		{
			$sql = "SELECT vehicleType, COUNT(icNo) FROM user JOIN owner USING (icNo) JOIN vehicle USING (vehicleID) WHERE userPosition='$who' AND userFaculty='$faculty' AND semester='$sem' GROUP BY vehicleType";
			
			$caption = "Graph of Type of Vehicles of " . $who . " in " . $faculty ;
			
		}
		else if ($for == "Contractor")
		{
			$sql = "SELECT vehicleType, COUNT(icNo) FROM user JOIN owner USING (icNo) JOIN vehicle USING (vehicleID) WHERE userPosition='$who' AND semester='$sem' GROUP BY vehicleType";
			
			$caption = "Graph of Type of Vehicles of " . $who;
			
		} else if ($for == "All")
		{
			$sql = "SELECT vehicleType, COUNT(icNo) FROM user JOIN owner USING (icNo) JOIN vehicle USING (vehicleID) WHERE semester='$sem' GROUP BY vehicleType";
			
			$caption = "Graph of Total Type of Vehicles of ";
			
		}
	} else if($for == "Select")
		echo "<script type='text/javascript'>alert('Select the result for what!!')</script>";
	/*
	if (!$sql)
	{
		echo "<script type='text/javascript'>alert('Please select an option!')</script>";
	}
	else */
	{
		$result=mysqli_query($con, $sql);
	
		if ($for == "list")
		{
			$arrData = array(
					"chart" => array(
					"caption" => $caption,
					"subCaption" => $sem,
					"xAxisName" => "Status",
					"yAxisName" => "Total Number",				
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
					"caption" => $caption,
					"subCaption" => $sem,
					"xAxisName" => "Vehicle Type",
					"yAxisName" => "Total Number",
					"showValues" => "0",
					"theme" => "zune"
					)
			);
		
			$arrData["data"] = array();
				
			while($row = mysqli_fetch_array($result)) 
			{
				array_push($arrData["data"], array(
						"label" => $row["vehicleType"], //palsi x
						"value" => $row["COUNT(icNo)"] // paksi y
						)
					);
			 }
		}
	
		 $jsonEncodedData = json_encode($arrData);
	
		 $columnChart = new FusionCharts("column2D", "myFirstChart" , 1000, 300, "chart-1", "json", $jsonEncodedData);
	
		  $columnChart->render();
	}
}
?>
</form>
</fieldset>
<div id="chart-1"><!-- Fusion Charts will render here--></div>
</div>
</article>


 <p>&nbsp;</p>
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
</script>
</body>
</html>