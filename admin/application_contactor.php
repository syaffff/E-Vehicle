<?php require_once('../Connections/dbConnUtem.php'); ?>
<?php require_once('../Connections/dbConn.php'); ?>

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


$colname_rsContractor = "-1";
if (isset($_GET['icNo'])) {
  $colname_rsContractor = $_GET['icNo'];
}
mysql_select_db($database_dbConnUtem, $dbConnUtem);
$query_rsContractor = sprintf("SELECT * FROM contractor WHERE icNo = %s", GetSQLValueString($colname_rsContractor, "text"));
$rsContractor = mysql_query($query_rsContractor, $dbConnUtem) or die(mysql_error());
$row_rsContractor = mysql_fetch_assoc($rsContractor);
$totalRows_rsContractor = mysql_num_rows($rsContractor);

$colname_rsUser = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUser = $_SESSION['MM_Username'];
}
mysql_select_db($database_dbConn, $dbConn);
$query_rsUser = sprintf("SELECT * FROM `user` WHERE icNo = %s", GetSQLValueString($colname_rsUser, "text"));
$rsUser = mysql_query($query_rsUser, $dbConn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO `user` (icNo, userPassword, userName, userAddress, userTel, userLicenseNo, userLicenseValidDate, userPosition) VALUES (%s, %s, %s, %s, %s, %s, %s, 'Contractor')",
                       GetSQLValueString($row_rsContractor['icNo'], "text"),
                       GetSQLValueString($_POST['userPassword'], "text"),
                       GetSQLValueString($row_rsContractor['userName'], "text"),
                       GetSQLValueString($row_rsContractor['userAddress'], "text"),
                       GetSQLValueString($row_rsContractor['userTel'], "text"),
                       GetSQLValueString($_POST['userLicenseNo'], "int"),
                       GetSQLValueString($_POST['userLicenseValidDate'], "date"));

  mysql_select_db($database_dbConn, $dbConn);
  $Result1 = mysql_query($insertSQL, $dbConn) or die(mysql_error());

  $insertGoTo = "register_vehicle_admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
			<section id="content" class="column-right">
                		
	    <article>
			
          
		  <h2>&nbsp;</h2>
			<h2>Register </h2>
		  <div class="article-info"></div>

            <p></p>
           
          <p>&nbsp;</p>
          <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
            <table align="center">
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Identification Card No:</td>
                  <td><?php echo $row_rsContractor['icNo']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Password:</td>
                  <td><input type="password" name="userPassword" value="" size="32" id="myInput" />
                  <input type="checkbox" onclick="myFunction()">  Show Password</td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Name:</td>
                  <td><?php echo $row_rsContractor['userName']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right" valign="top">Address:</td>
                  <td><?php echo $row_rsContractor['userAddress']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Telephone No.:</td>
                  <td><?php echo $row_rsContractor['userTel']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">LicenseNo:</td>
                  <td><input type="text" name="userLicenseNo" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <?php 
                  $timezone = date_default_timezone_get();
                  $min = date('Y-m-d', time());
                  ?>
                  <td nowrap="nowrap" align="right">License Valid Date:</td>
                  <td><input type="date" name="userLicenseValidDate" value="" size="32" min="<?php echo $min ?>"/></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">&nbsp;</td>
                  <td><input type="submit" value="Insert record" /></td>
                </tr>
              </table>
              <input type="hidden" name="MM_insert" value="form2" />
          </form>
            <p>&nbsp;</p>
		


		
		</article>
	
		<article class="expanded">

            		<h2></h2>
			<div class="article-info">Posted on <time datetime="2013-05-14">14 May</time> by <a href="#" rel="author">Joe Bloggs</a></div>

			
            



<p>Morbi fermentum condimentum felis, commodo vestibulum sem mattis sed. Aliquam magna ante, mollis vitae tincidunt in, malesuada vitae turpis. Sed aliquam libero ut velit bibendum consectetur. Quisque sagittis, est in laoreet semper, enim dui consequat felis, faucibus ornare urna velit nec leo. Maecenas condimentum velit vitae est lobortis fermentum. In tristique sem vitae metus ornare luctus tempus nisl volutpat. Integer et est id nisi tempus pharetra sagittis et libero.</p>


		<a href="#" class="button">Read more</a>
		<a href="#" class="button button-reversed">Comments</a>
		</article>

			
			<footer class="clear">
				<p>&nbsp;</p>
			</footer>

		</section>

		<div class="clear"></div>

	</section>
	<script>
function myFunction() {
    var x = document.getElementById("myInput");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
</script>

</body>
</html>
<?php
mysql_free_result($rsContractor);

mysql_free_result($rsUser);
?>
