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

<form id="form1" name="form1" method="get" action="">
  <label for="select"></label>
  <select name="select" id="select">
    <option selected="selected">Select</option>
    <option value="1">Student - Approval List</option>
    <option value="2">Lecturer - Approval List</option>
    <option value="3">Contractor - Approval List</option>
    <option value="4">General - Approval List</option>
    <option value="5">General - Vehicle Type</option>
    <option value="6">General - User</option>
  </select>
  <input type="submit" name="submit" id="submit" value="Submit" />
</form>

<div class="graph">
<?php

include("fusioncharts.php");
require("../Connections/dbConn.php");

$con=mysqli_connect ('localhost','root','','utemsticker');
$sqlFTMK  = "";
$sqlFKP   = "";
$sqlFKEKK = "";
$sqlFKE   = "";
$sqlFTK   = "";
$sqlFKM   = "";
$sqlFPTT  = "";

if(isset($_GET["submit"]))
{
	echo "masuk1";
	
	$sql =  "SELECT status, COUNT(icNo) FROM user JOIN owner USING (icNo) WHERE userPosition='Student'GROUP BY status";
			 
	$result=mysqli_query($con, $sql);

  	$arrData = array(
      	    "chart" => array(
            "caption" => "Approval List of Student",
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
			
     $jsonEncodedData = json_encode($arrData);

     $columnChart = new FusionCharts("column2D", "myFirstChart" , 1000, 300, "chart-1", "json", $jsonEncodedData);

      $columnChart->render();

?>
<div id="chart-1"><!-- Fusion Charts will render here--></div>
</div>
<?php
}
?>
		
		
	
}

?>
</body>
</html>