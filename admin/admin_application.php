<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../css/menu.css" rel="stylesheet" type="text/css" />
<link href="../css/layout.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin-Application</title>
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
    
    color: black;
}
td:hover{background-color:#4CAF50}
</style>
</head>

<body>

<div id="Holder">
<div id="Header"></div>
<div id="NavBar">
	<nav>
   	  <ul>
        	<li>Sticker 
            <ul>
            	<li><a href="daftar.php">New Register</a></li>
                <li><a href="">Approve</a></li>
                <li><a href="">Saman</a></li>
                </ul>
            </li>
            <li><a href=""> Vehicle </a></li>
            <li><a href="admin_approve.php"> Search </a></li>
            <li><a href=""> Statistic </a></li>
         
        </ul>
    </nav>
</div>
<br><br><br>

<div class="update_form">
<?php
include('../Connections/dbcode.php');

$status="DALAM PROSES";

$sql = mysqli_query($conn,'SELECT u.* , o.* FROM user u , owner o WHERE status = "'.$status.'" and o.icNo=u.icNo');

if($sql->num_rows)

	{
		echo"<form  action='admin_app_approve.php' method='GET'>
				<table width='1000' height='209' border='1'>
  				<tr>
  				<th width='100' align='center'>IDENTITY CARD NUMBER(NRIC)</th>
    			<th width='100' align='center'>FULLNAME</th>
    			<th width='100' align='center'>ADDRESS</th>
    			<th width='100' align='center'>PHONE NUMBER</th>
    			<th width='200' align='center'>STATUS</th>
    			<th width='200' align='center'>OPTION</th>
    			</tr>";	

	

		while($row = mysqli_fetch_array($sql))
		{
	
		$icno = $row['icNo'];
		$name = $row['userName'];
		$address = $row['userAddress'];
		$phone = $row['userTel'];
		$status = $row['status'];
	
			echo"
    			<tr>
    			<td>$icno</td>
    			<td>$name</td>
   				<td>$address</td>
   				<td>$phone</td>
   				<td>$status</td>
   				
   				<td align='center'><a href='admin_app_approve.php?detail=$icno'>
				<img src='icon/detail.png' height= 27px; width= 27px;><br>Details</a>
				
				<br><br>
				<a href='admin_app_approve.php?approve=$icno'>
				<img src='icon/approve.png' height= 27px; width= 27px;><br>Approve</a>
				</td>
				</tr>";
				
		
		}
				"</table>
				
				</form>";
	}

	

	/*else
	{

		echo "<script>
						alert('Record Not Found')
						
					</script>";
			header("refresh:0 url=deletestudent.php");
			

				$sql1->close();	

		} */
	
	

echo "<br><br>";
?>

</div>
</body>
</html>