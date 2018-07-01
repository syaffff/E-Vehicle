<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
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
<form id="form1" name="form1" method="post" action="">

  <p>
    What For?
    <select name="for" id="for">
      <option selected="selected">Select</option>
      <option value="list">Approval List</option>
      <option value="type">Type of Vehicles</option>
    </select>
  <p>
  	Semester
    <select name="sem" id="sem">
      <option selected="selected">Select</option>
      <option value="Semester 1 2016/2017">Semester 1 2016/2017</option>
      <option value="Semester 2 2016/2017">Semester 2 2016/2017</option>
      <option value="Semester 1 2017/2018">Semester 1 2017/2018</option>
      <option value="Semester 2 2017/2018">Semester 2 2017/2018</option>
    </select>
  <p>
     Who:
    <select name="who" id="who" onChange="myFunction(this.value)">
      <option selected="selected">Select</option>
      <option value="Student">Student</option>
      <option value="Lecturer">Lecturer</option>
      <option value="Contractor">Contractor</option>
      <option value="All">All</option>
    </select>
  <p>
    Faculty
    <select name="faculty" id="faculty">
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
  <p>
    <input type="submit" name="submit" id="submit" value="Submit" />
  </p>
</form>

<div class="graph">
<?php

include("fusioncharts.php");
require("../Connections/dbConn.php");

$con=mysqli_connect ('localhost','root','','utemsticker');


if(isset($_POST["submit"]))
{
	$for = "";
	$sem = "";
	$who = "";
	$faculty = "";
	
	$caption = "";
	$subCaption = "";
	
	if ($_POST['for'])
		$for = $_POST['for'];
		
	if ($_POST['sem'])
		$sem = $_POST['sem'];
		
	if ($_POST['who'])
		$who = $_POST['who'];
	
	if ($_POST['faculty'])
		$faculty = $_POST['faculty'];
	
	//echo "For : ". $for;
	echo "Semester : ". $sem;
	echo "<p>";
	echo "Who      : ". $who;
	echo "<p>";
	echo "Faculty  : ". $faculty;
	echo "<p>";
	echo "<p>";
	
	$sql = "";
	
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

?>
<div id="chart-1"><!-- Fusion Charts will render here--></div>
</div>
<?php
}
?>
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