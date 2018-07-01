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

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin-Application</title>

<link rel="stylesheet" href="../3372/styles.css" type="text/css" />
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />

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
                  <li class="selected-item"><a href="register_search_admin.php"> Sticker Application </a></li>
                  <li><a href="admin_home.php"> Sticker Appllication List </a></li>
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
<br><h2 align="center">Please Enter Information Below.</h2>

<div class="search_form">


<form action="register_details.php" method="get">
          <div class="field-wrap">
          <label>Identification Card No<span class="req">*<br></span></label>
          <input type="text" name="icNo" required autocomplete="off"/></div>
          <button name="btn_search" class="button button-block"/>Get Started</button>

</form>
</div>

</fieldset>
</p>


<p>
<fieldset>

<?php
      if(isset($_GET["btn_search"]))
      {
       
        $main_db1 = false;
        $main_db2 = false;
        $main_db3 = false;
        $sticker = false;
        
        $con1 = mysqli_connect('localhost', 'root', '', 'maindb_utem');
        $con2 = mysqli_connect('localhost', 'root', '', 'utemsticker');
        
        $sql1 = "SELECT * FROM lecturer WHERE icNo='$_GET[icNo]'";
        
        if($result1 = mysqli_query($con1, $sql1))
        {
          $emparray = array();
          $count = mysqli_num_rows($result1);
          if($count == 1)
          {
             $main_db1 = true;        
           
          } 
          else
          {
            $main_db1 = false;
          }
        }
        
        $sql2 = "SELECT * FROM student WHERE icNo='$_GET[icNo]'";
        
        if($result2 = mysqli_query($con1, $sql2))
        {
          $emparray = array();
          $count = mysqli_num_rows($result2);
          if($count == 1)
          {
            $main_db2 = true;
           
          }else
          {
            $main_db2 = false;
          }
        }
        $sql3 = "SELECT * FROM contractor WHERE icNo='$_GET[icNo]'";
        
        if($result3 = mysqli_query($con1, $sql3))
        {
          $emparray = array();
          $count = mysqli_num_rows($result3);
          if($count == 1)
          {
            $main_db3 = true;

          }else{
            $main_db3 = false;
          }
        }
        
        //mysqli_close($con1);
        
       
        $sql4 = "SELECT * FROM user WHERE icNo='$_GET[icNo]'";
        
        if($result4 = mysqli_query($con2, $sql4))
        {
          $emparray = array();
          $count = mysqli_num_rows($result4);
          if($count == 1)
          {
            $sticker = true;

          } else
          {
            $sticker = false;
          }
          
        }
          
       if (($main_db1 || $main_db2 || $main_db3) && !($sticker))
        {
          
          if ($main_db1)
          {
            echo "<script>
            alert('This User Is Not Register In The System Yet.')
            
            </script>";
          header("refresh:0 url=application_lect.php?icNo=$_GET[icNo]");
            
          }
          
          else if ($main_db2)
          {
            echo "<script>
            alert('This User Is Not Register In The System Yet.')
            
            </script>";
          header("refresh:0 url=application_stud.php?icNo=$_GET[icNo]");
          }
          
          else if ($main_db3)
          {
            echo "<script>
            alert('This User Is Not Register In The System Yet.')
            
            </script>";
          header("refresh:0 url=application_contactor.php?icNo=$_GET[icNo]");
          }
          
           
        } 

      else if (($main_db1 || $main_db2 || $main_db3) && ($sticker))
      {
           if ($main_db1)
          {
            echo "<script>
            alert('Here The User Details.')
            
            </script>";

            if($result1->num_rows)

            {
              echo"
              <br><br><h2 align='center'>User Details.</h2>
              <form  action='deletestudent.php' method='GET'>
                  <table width='1000' height='209' border='1'>
                  <tr>
                  <th width='100' align='center'>IDENTITY CARD NUMBER(NRIC)</th>
                  <th width='100' align='center'>STAFF ID</th>
                  <th width='100' align='center'>FULLNAME</th>
                  <th width='100' align='center'>FACULTY</th>
                  <th width='200' align='center'>ADDRESS</th>
                  <th width='100' align='center'>PHONE NUMBER</th>
                  <th width='100' align='center'>OPTION</th>
                  </tr>";
                  while($row = mysqli_fetch_array($result1))
                    {
                  
                    $icno = $row['icNo'];
                    $userID = $row['userID'];
                    $name = $row['userName'];
                    $userFaculty =$row['userFaculty'];
                    $address = $row['userAddress'];
                    $phone = $row['userTel'];
                   
                    echo"
                        <tr>
                        <td>$icno</td>
                        <td>$userID</td>
                        <td>$name</td>
                        <td>$userFaculty</td>
                        <td>$address</td>
                        <td>$phone</td>
                
                  
                  
                <td align='center'><a href='register_vehicle_admin.php?apply=$icno'>
                <img src='icon/apply.png' height= 27px; width= 27px;><br>Apply Now</a>       
                </td>
                </tr>";
              
              }
                "</table>
                
                </form>";

                $result1->close();

            }
         
            
          }
          
          else if ($main_db2)
          {
            echo "<script>
            alert('Here The User Details.')
            
            </script>";


            if($result2->num_rows)

            {
               echo"
               <br><br><h2 align='center'>User Details.</h2>
               <form  action='deletestudent.php' method='GET'>
                  <table width='1000' height='209' border='1'>
                  <tr>
                  <th width='100' align='center'>IDENTITY CARD NUMBER(NRIC)</th>
                  <th width='100' align='center'>MATRIC NUMBER</th>
                  <th width='100' align='center'>FULLNAME</th>
                  <th width='100' align='center'>FACULTY</th>
                  <th width='200' align='center'>ADDRESS</th>
                  <th width='100' align='center'>PHONE NUMBER</th>
                  <th width='100' align='center'>OPTION</th>
                  </tr>";
                  while($row = mysqli_fetch_array($result2))
                    {
                  
                    $icno = $row['icNo'];
                    $userID = $row['userID'];
                    $name = $row['userName'];
                     $userFaculty =$row['userFaculty'];
                    $address = $row['userAddress'];
                    $phone = $row['userTel'];
                   
                    echo"
                        <tr>
                        <td>$icno</td>
                        <td>$userID</td>
                        <td>$name</td>
                        <td>$userFaculty</td>
                        <td>$address</td>
                        <td>$phone</td>
                
                  
                  
                <td align='center'><a href='register_vehicle_admin.php?apply=$icno'>
                <img src='icon/apply.png' height= 27px; width= 27px;><br>Apply Now</a>       
                </td>
                </tr>";
              
              }
                "</table>
                
                </form>";

                $result2->close();
            }
          
          }
          
          else if ($main_db3)
          {
            echo "<script>
            alert('Here The User Details.')
            
            </script>";

            if($result3->num_rows)

            {
              echo"
              <br><br><h2 align='center'>User Details.</h2>
              <form  action='deletestudent.php' method='GET'>
                  <table width='1000' height='209' border='1'>
                  <tr>
                  <th width='100' align='center'>IDENTITY CARD NUMBER(NRIC)</th>
                  
                  <th width='100' align='center'>FULLNAME</th>
                 
                  <th width='200' align='center'>ADDRESS</th>
                  <th width='100' align='center'>PHONE NUMBER</th>
                  <th width='100' align='center'>OPTION</th>
                  </tr>";
                    while($row = mysqli_fetch_array($result3))
                      {
                    
                      $icno = $row['icNo'];
                     
                      $name = $row['userName'];
                      
                      $address = $row['userAddress'];
                      $phone = $row['userTel'];
                     
                      echo"
                          <tr>
                          <td>$icno</td>
                         
                          <td>$name</td>
                         
                          <td>$address</td>
                          <td>$phone</td>
                
                  
                  
                <td align='center'><a href='register_vehicle_admin.php?apply=$icno'>
                <img src='icon/apply.png' height= 27px; width= 27px;><br>Apply Now</a>       
                </td>
                </tr>";
              
              }
                "</table>
                
                </form>";

                $result3->close();
            }
          
          }
      }

      else
      
        {
          echo "<script type='text/javascript'>alert('No records')</script>";
        }

        






      }

  ?>


</fieldset>
</p>

<article class="expanded">
  <br><br><br><br> <br><br><br><br> <br><br> <br>   

   </article>
 
	</section>

  <br><br><br>
  <footer class="clear">
            
        <p>&nbsp;</p>
      </footer>
  </section>

  

</body>
</html>