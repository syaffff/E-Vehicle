<?php require_once('../Connections/dbConn.php'); ?>
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
	
  $logoutGoTo = "../login.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
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

$colname_rsUser = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUser = $_SESSION['MM_Username'];
}
mysql_select_db($database_dbConn, $dbConn);
$query_rsUser = sprintf("SELECT * FROM `user` WHERE icNo = %s", GetSQLValueString($colname_rsUser, "text"));
$rsUser = mysql_query($query_rsUser, $dbConn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

$maxRows_rsSummon = 10;
$pageNum_rsSummon = 0;
if (isset($_GET['pageNum_rsSummon'])) {
  $pageNum_rsSummon = $_GET['pageNum_rsSummon'];
}
$startRow_rsSummon = $pageNum_rsSummon * $maxRows_rsSummon;

$colname_rsSummon = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsSummon = $_SESSION['MM_Username'];
}
mysql_select_db($database_dbConn, $dbConn);
$query_rsSummon = sprintf("SELECT * FROM summon WHERE icNo = %s", GetSQLValueString($colname_rsSummon, "text"));
$query_limit_rsSummon = sprintf("%s LIMIT %d, %d", $query_rsSummon, $startRow_rsSummon, $maxRows_rsSummon);
$rsSummon = mysql_query($query_limit_rsSummon, $dbConn) or die(mysql_error());
$row_rsSummon = mysql_fetch_assoc($rsSummon);

if (isset($_GET['totalRows_rsSummon'])) {
  $totalRows_rsSummon = $_GET['totalRows_rsSummon'];
} else {
  $all_rsSummon = mysql_query($query_rsSummon);
  $totalRows_rsSummon = mysql_num_rows($all_rsSummon);
}
$totalPages_rsSummon = ceil($totalRows_rsSummon/$maxRows_rsSummon)-1;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8; " />
<title>Untitled Document</title>
<link rel="stylesheet" href="../3372/styles.css" type="text/css" />
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
</head>

<body>

		<section id="body" class="width">
<aside id="sidebar" class="column-left">

			<header>
				<h1><a href="#">UTeM</a></h1>

				<h2><?php echo $row_rsUser['userName'] ?></h2>
				
			</header>

			<nav id="mainnav">
  				<ul>
                <li><a href="application.php">Application</a></li>
                <li class="selected-item"><a href="summon.php">Summon</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="<?php echo $logoutAction ?>" onclick="return confirm('Are you sure to logout?');">Log Out</a></li>
           	  </ul>
			</nav>

			
			
			</aside>
			<section id="content" class="column-right">
            
             <fieldset>
		  <h2>&nbsp;</h2>
          <?php 
				  $timezone = date_default_timezone_get();
				  $min = date('Y-m-d', time());
		  ?>
			<p><form id="form2" name="form2" method="get" action="">
            <table align="center" border="0" cellspacing="0" cellpadding="6">
              <tr>
                <td width="227" align="right"><label> From:
				<input type="date" name="fineDateTime1" id="search" max="<?php echo $min ?>"/>
                </label></td>
                 <td width="199" align="right"><label>To: 
                    <input type="date" name="fineDateTime2" id="search" max="<?php echo $min ?>"/>
                </label></td>
                <td width="857" align="left"><input type="submit" name="btn_search" value="Search" /></td>
              </tr>
              
<?php
if(isset($_GET["btn_search"]))
{

	$from = date_create($_GET["fineDateTime1"]);
	$to = date_create($_GET["fineDateTime2"]);
	
	date_time_set($from, 00, 00);
	$from = date_format($from, 'Y-m-d H:i:s');
	
	date_time_set($to, 00, 00);
	$to = date_format($to, 'Y-m-d H:i:s');
				  
	$con = mysqli_connect('localhost', 'root', '', 'utemsticker');
	$sql = "SELECT * FROM summon JOIN fine USING (fineID) where icNo='$_SESSION[MM_Username]' AND fineDateTime >= '$from' AND fineDateTime <= '$to' ";
	
	if($result = mysqli_query($con, $sql))
				{
					$emparray = array();
					$count = mysqli_num_rows($result);
					if($count != 0)
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
			echo "<th width=7%>"; echo "Fine"; echo "</th>";
			echo "<th width=7%>"; echo "Plate No"; echo "</th>";
			echo "<th width=7%>"; echo "Time & Date"; echo "</th>";
			echo "<th width=7%>"; echo "Place"; echo "</th>";
			echo "<th width=7%>"; echo "Payment"; echo "</th>";
			echo "<th width=7%>"; echo "Status"; echo "</th>";
		echo "</tr>";
		
		while ($row=mysqli_fetch_array($result))
		{
			echo "<tr>";
			echo "<td>"; echo $row["fineType"]; echo "</td>";
			echo "<td>"; echo $row["vehicleID"]; echo "</td>";
			echo "<td>"; echo $row["fineDateTime"]; echo "</td>";
			echo "<td>"; echo $row["finePlace"]; echo "</td>";
			echo "<td>"; echo "RM " . $row["finePayment"] . ".00"; echo "</td>";
			echo "<td>"; echo $row["fineStatus"]; echo "</td>";
			echo "</tr>";
		}
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		
	} else
	{
    	echo "<script type='text/javascript'>alert('No records found')</script>";
	}
	
	mysqli_close($con);
}
?>  
              
            </table>
          </form>
          
      
		</fieldset>
            
            
                		
	    <article>
			
          
		  <h2>&nbsp;</h2>
			<h2>&nbsp;</h2>
		  <div class="article-info"></div>
          
          <fieldset>
          <legend> All Records </legend>
          <?php
		  if ($row_rsSummon['fineID'])
		  {
		  ?>
          <table id = "summon">
            <tr>
              <th>Fine ID</th>
              <th>Plate Number</th>
              <th>Date &amp; Time</th>
              <th>Place</th>
              <th>Payment</th>
              <th>Status</th>
            </tr>
            <?php do { ?>
              <tr>
                <td><?php echo $row_rsSummon['fineID']; ?></td>
                <td><?php echo $row_rsSummon['vehicleID']; ?></td>
                <td><?php echo $row_rsSummon['fineDateTime']; ?></td>
                <td><?php echo $row_rsSummon['finePlace']; ?></td>
                <td>RM <?php echo $row_rsSummon['finePayment']; ?></td>
                <td><?php echo $row_rsSummon['fineStatus']; ?></td>
            </tr>
              <?php } while ($row_rsSummon = mysql_fetch_assoc($rsSummon)); ?>
          </table>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
            <?php
		  }
          
          else
          { 
		  		echo "<br>"; echo "<br>";
		  		echo "No records";
		  } ?>		
          </p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          
<script>
function printData()
{ 
	
	var divToPrint=document.getElementById("summon");
  	newWin= window.open("");
   	newWin.document.write(divToPrint.outerHTML);
   	newWin.print();
   	newWin.close();
   	


}
</script> 
            
          </form>
          
          
          <p>&nbsp;</p>
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
	

</body>
</html>
<script>
function myFunction() {
    var x = document.getElementById("search");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
</script>
<?php
mysql_free_result($rsUser);

mysql_free_result($rsSummon);
?>
