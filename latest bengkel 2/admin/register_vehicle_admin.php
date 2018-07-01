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
    exit;
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) 
{
  $insertSQL = sprintf("INSERT INTO vehicle (vehicleID, vehicleType, vehicleRoadTaxDate) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['vehicleID'], "text"),
                       GetSQLValueString($_POST['vehicleType'], "text"),
                       GetSQLValueString($_POST['vehicleRoadTaxDate'], "date"));
  if((isset($_GET['apply'])))
  {
    $insertSQL2 = sprintf("INSERT INTO owner (vehicleID, icNo, status, semester) VALUES (%s, %s, 'IN PROCESS', 'SEMESTER 2 2017/2018')",
              GetSQLValueString($_POST['vehicleID'], "text"),
            GetSQLValueString($_GET['apply'], "text"));

  mysql_select_db($database_dbConn, $dbConn);
  $Result1 = mysql_query($insertSQL, $dbConn) or die(mysql_error());
  $Result2 = mysql_query($insertSQL2, $dbConn) or die (mysql_error());

  echo "<script>
            alert('Vehicle Data Has Been Insert In The Database')
            
          </script>";
      header("refresh:0 url=admin_home.php");
  }
   
  else
  {
    $insertSQL2 = sprintf("INSERT INTO owner (vehicleID, icNo, status, semester) VALUES (%s, %s, 'IN PROCESS', 'SEMESTER 2 2017/2018')",
              GetSQLValueString($_POST['vehicleID'], "text"),
            GetSQLValueString($_GET['icNo'], "text"));

  mysql_select_db($database_dbConn, $dbConn);
  $Result1 = mysql_query($insertSQL, $dbConn) or die(mysql_error());
  $Result2 = mysql_query($insertSQL2, $dbConn) or die (mysql_error());
  }

  echo "<script>
            alert('Vehicle Data Has Been Insert In The Database')
            
          </script>";
      header("refresh:0 url=admin_home.php");         
  
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
if (isset($_GET['icNo'])) {   
  $colname_rsOwner = $_GET['icNo'];
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8; " />
<title>Untitled Document</title>
<link rel="stylesheet" href="../3372/styles.css" type="text/css" />
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</head>

<body>

    <section id="body" class="width">
    <aside id="sidebar" class="column-left">

      <header>
        <h1><a href="#">UTeM</a></h1>

        <h2><?php echo $row_rsUser['userName'] ?> [Admin]</h2>
        
      </header>

     <nav id="mainnav">
          <ul>
                 <li class="selected-item"><a href="register_search_admin.php"> Sticker Application </a></li>
                  <li><a href="admin_home.php"> Sticker Appllication List </a></li>
                  <li><a href="admin_search.php"> Approved Sticker List </a></li>
                  <li><a href="summon.php"> Summon </a></li>
                  <li><a href="vehicle.php"> Vehicle </a></li>
                  <li><a href=""> Statistic </a></li>
                  <li><a href="<?php echo $logoutAction ?>">Log Out</a></li>
              </ul>
      </nav>

      
      
      </aside>
      <section id="content" class="column-right">     
          <fieldset>
      <p><form id="form2" name="form2" method="get" action="">
            <table align="center" border="0" cellspacing="0" cellpadding="6">
              <tr>
                <td width="241" align="right"><label>Plate No:  
                    <input type="text" name="vehicleID" id="search" />
                </label></td>
                <td width="259" align="left"><input type="submit" name="btn_search" value="Search" /></td>
              </tr>
              
              <?php
if(isset($_GET["btn_search"]))
{
  $check = false;
  $con = mysqli_connect('localhost', 'root', '', 'utemsticker');
  $sql = "SELECT * FROM owner JOIN vehicle USING (vehicleID) where vehicleID='$_GET[vehicleID]' AND icNo='$_SESSION[MM_Username]'";
  //$result = mysqli_query($con, $sql);
  
  if($result = mysqli_query($con, $sql))
        {
          $emparray = array();
          $count = mysqli_num_rows($result);
          if($count == 1)
          {
            $check = true;
          } else
          {
            $check = false;
          }
        }
  
  if ($check)
  {
    echo "<table>";
    echo "<tr>";
      echo "<th width=7%>"; echo "Plate No"; echo "</th>";
      echo "<th width=7%>"; echo "Type"; echo "</th>";
      echo "<th width=9%>"; echo "Road Tax Date"; echo "</th>";
      echo "<th width=7%>"; echo "Status"; echo "</th>";
      echo "<th width=7%>"; echo "Semester"; echo "</th>";
    echo "</tr>";
    
    while ($row=mysqli_fetch_array($result))
    {
      echo "<tr>";
      echo "<td>"; echo $row["vehicleID"]; echo "</td>";
      echo "<td>"; echo $row["vehicleType"]; echo "</td>";
      echo "<td>"; echo $row["vehicleRoadTaxDate"]; echo "</td>";
      echo "<td>"; echo $row["status"]; echo "</td>";
      echo "<td>"; echo $row["semester"]; echo "</td>";
      echo "</tr>";
    }
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "<br>";
    
  } else
  {
    echo "No results found";
  }
  
  mysqli_close($con);
}
?>  
              
            </table>
          </form> 
    </fieldset>
          </p>
          <p>&nbsp;</p>
          <article>
          <fieldset>
          <legend>Register New Vehicle</legend>
          <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
           <table>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">Plate No:</td>
                <td align="left"><span id="sprytextfield1">
                <input type="text" name="vehicleID" value="" size="32" />
                <span class="textfieldRequiredMsg">A value is required.</span></span></td>
              </tr>
              <tr valign="baseline">
                <td align="right" valign="middle" nowrap="nowrap">Type:</td>
                <td align="left" valign="baseline"><table>
                  <tr>
                    <td align="left"><input type="radio" name="vehicleType" value="Car" />
                      Car</td>
                  </tr>
                  <tr>
                    <td align="left"><input type="radio" name="vehicleType" value="Motorcycle" />
                      Motorcycle</td>
                  </tr>
                  <tr>
                    <td align="left"><input type="radio" name="vehicleType" value="Others" />
                      Others</td>
                  </tr>
                </table></td>
              </tr>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">Road Tax Date:</td>
                <td align="left"><input type="date" name="vehicleRoadTaxDate" value="" size="32" /></td>
              </tr>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right"><input type="reset" class="formbutton" name="reset" id="reset" value="Reset" /></td>
                <td align="left"><input type="submit" class="formbutton" value="Register" /></td>
              </tr>
            </table>              
            <input type="hidden" name="MM_insert" value="form1" />
             <input type="hidden" name="icNo" value="$_POST['icNo']" />
          </form>
          </fieldset>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <fieldset>
          <legend> Registered Vehicles </legend>
          <p>&nbsp;</p>
          
          
          <?php 
      if ($row_rsOwner['vehicleID'])
      {
      
          echo "<table>";
            echo "<tr>";
              echo "<th>"; echo "Plate No"; echo "</th>";
              echo "<th>"; echo "Status"; echo "</th>";
        echo "<th>"; echo "Semester"; echo "</th>";
              echo "<th>"; echo "Action"; echo "</th>";
            echo "</tr>";
      
             do 
       {
              echo "<tr>";
                echo "<td>"; echo $row_rsOwner['vehicleID']; echo "</td>";
                echo "<td>"; echo $row_rsOwner['status']; echo "</td>";
        echo "<td>"; echo $row_rsOwner['semester']; echo "</td>";
        
                if ($row_rsOwner['status'] == "ACTIVE")
        {
                   echo "<td align=center>"; 
           echo "<form id=form3 name=form3 method=post action=>";
                   echo "<input type=submit name=Deactive id=Deactive value=Deactive onClick='return confirm('Confirm deactive?')'/>";
                   echo "<input type=hidden name=MM_insert value=form3 />";
                   echo "</form>";
           echo "</td>";
  
          if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) 
          {
            $updateSQL = sprintf("UPDATE `owner` SET status='INACTIVE' WHERE icNo=%s AND vehicleID=%s",
               GetSQLValueString($_SESSION['MM_Username'], "text"),
               GetSQLValueString($row_rsOwner['vehicleID'], "text"));
               
            mysql_select_db($database_dbConn, $dbConn);
            $Result1 = mysql_query($updateSQL, $dbConn) or die(mysql_error());
          }
        }
        else if ($row_rsOwner['status'] == "INACTIVE")
        {
           echo "<td align=center>"; 
           echo "<form id=form4 name=form4 method=post action=>";
                   echo "<input type=submit name=Reactive id=Reactive value=Activate onClick='return confirm('Confirm reactive?')'/>";
                   echo "<input type=hidden name=MM_insert value=form4 />";
                   echo "</form>";
           echo "</td>";
  
          if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form4")) 
          {
            $updateSQL1 = sprintf("UPDATE `owner` SET status='IN PROCESS' WHERE icNo=%s AND vehicleID=%s",
               GetSQLValueString($_SESSION['MM_Username'], "text"),
               GetSQLValueString($row_rsOwner['vehicleID'], "text"));
               
            mysql_select_db($database_dbConn, $dbConn);
            $Result2 = mysql_query($updateSQL1, $dbConn) or die(mysql_error());
          }
        }
        
        else
        {
                echo "<td>"; echo "</td>";
                 } 
              echo "</tr>";
               } while ($row_rsOwner = mysql_fetch_assoc($rsOwner)); 
         
         echo "</table>";
          } 
      
      else 
      {
        echo "No records";
      }?>
          
          </fieldset>
    </article>
      <footer class="clear">
            <p>Pejabat Keselamatan | Universiti Teknikal Malaysia Melaka, Hang Tuah Jaya, 76100 Durian Tunggal, Melaka, Malaysia. 
Phone: +606-3316371   |   Fax: +606-3316344   |  hdajssadn dosjdsjdnaus dnxjskn nxosandjksanxa ndjsknxjknas dxasndjksandjkasndjsdnksndjksdnsjkndjsndsjandjadnjasdnajdnjakdnasjdnadajndasjkdasdnasjdnskjdnjk </p>
        <p>&nbsp;</p>
      </footer>

    </section>

    <div class="clear"></div>

  </section>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none");
        </script>
</body>
</html>
<?php
mysql_free_result($rsUser);

mysql_free_result($rsVehicle);

mysql_free_result($rsOwner);
?>
