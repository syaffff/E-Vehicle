

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../CSS/layout.css" rel="stylesheet" type="text/css" />
<link href="../CSS/menu.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Online Examination Portal</title>
</head>

<body>
<div id="Holder">
<div id="Header"></div>
<div id="NavBar">
	<nav>
   	  <ul>
        	<li><a href="adminMain.php">        Main       </a></li>
            <li><a href="adminStudent1.php">      Students     </a></li>
            <li><a>  Question Papers  </a></li>
            <li><a href="adminViewRecord.php">Records</a></li>
            <li><a>       Profile     </a></li>
            <li><a href="option.php">       Log Out     </a></li>
        </ul>
    </nav>
</div>
<div id="Content">
	<div id="PageHeading">
	  <h1>&nbsp;</h1>
	</div>

    <form name='form1' action="" method="get">
    <table width="473">
    	<tr>
        	<td width="106" align="left"> Exam ID      :</td>
            <td width="355"><input name="examID" type="text" />
              <label for="select"></label>              
<input type="submit" name="btn_submit" value="Enter" /></td>
        </tr>
        <tr>
        	<td colspan="2" align="center"></td>
        </tr>  
    </table>
	</form>
    

<?php
if(isset($_GET["btn_submit"]))
{
?>
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
	width: 100%;
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
.
<body>

<div class="graph">

<?php 
$con=mysqli_connect ('localhost','root','','exam')
?>


<?php

include("fusioncharts.php");
		
		
			 $sql =  "SELECT DISTINCT grade, COUNT(student_ID) FROM student JOIN mark USING (student_ID) JOIN exam USING (exam_ID) WHERE Exam_ID = '$_GET[examID]' GROUP BY (grade)";
			 
			 $result=mysqli_query($con, $sql);

	

     	
        	$arrData = array(
        	    "chart" => array(
                  "caption" => "Number Of Students' Grade For Each Exam",
                  "showValues" => "0",
                  "theme" => "zune"
              	)
           	);

        	$arrData["data"] = array();
		
		while($row = mysqli_fetch_array($result)) {
           	array_push($arrData["data"], array(
              	"label" => $row["grade"], //palsi x
              	"value" => $row["COUNT(student_ID)"] // paksi y
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




<div id="Footer"></div>
</div>
</body>
</html>