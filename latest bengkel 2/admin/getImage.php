<?php
include('../Connections/dbcode.php');

if(isset($_GET['icNo']))
    {
    	$icno=$_GET['icNo'];

    	$sql1 = mysqli_query($conn,'SELECT * FROM user WHERE icNo = "'.$icNo.'"');

    	 $result = mysqli_query($conn,'$sql1');
  		$row = mysqli_fetch_assoc($result);
  		

  header("Content-type: image/jpg");
  echo $row['icFile'];
    }
?>