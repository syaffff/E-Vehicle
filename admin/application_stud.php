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


$colname_rsStudent = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsStudent = $_SESSION['MM_Username'];
}
if (isset($_SESSION['icNo']) && isset($_SESSION['ext'])) {
  $colname_rsStudent = $_SESSION['icNo'];
}
$colname_rsStudent = "-1";
if (isset($_GET['icNo'])) {
  $colname_rsStudent = $_GET['icNo'];
}
mysql_select_db($database_dbConnUtem, $dbConnUtem);
$query_rsStudent = sprintf("SELECT * FROM student WHERE icNo = %s", GetSQLValueString($colname_rsStudent, "text"));
$rsStudent = mysql_query($query_rsStudent, $dbConnUtem) or die(mysql_error());
$row_rsStudent = mysql_fetch_assoc($rsStudent);
$totalRows_rsStudent = mysql_num_rows($rsStudent);

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2") )  
{
	
	$check1 = getimagesize($_FILES["icFile"]["tmp_name"]);
	$check2 = getimagesize($_FILES["licenseFile"]["tmp_name"]);
	$check3 = getimagesize($_FILES["cardFile"]["tmp_name"]);
	
					   
					   $sql = "INSERT INTO user (icNo, userID, userPassword, userName, userResidential, userFaculty, userEduLevel, userAddress, userTel, userLicenseNo, userLicenseValidDate, userPosition) VALUES ('$row_rsStudent[icNo]', '$row_rsStudent[userID]', '$_POST[userPassword]', '$row_rsStudent[userName]', '$row_rsStudent[userResidential]', '$row_rsStudent[userFaculty]', '$row_rsStudent[userEduLevel]', '$row_rsStudent[userAddress]', '$row_rsStudent[userTel]', '$_POST[userLicenseNo]', '$_POST[userLicenseValidDate]', 'Student')";
					   
	  mysql_select_db($database_dbConn, $dbConn);
	  $Result1 = mysql_query($sql, $dbConn) or die(mysql_error());
	
	  $insertGoTo = "register_vehicle_admin.php";
	  if (isset($_SERVER['QUERY_STRING'])) 
	  {
		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		$insertGoTo .= $_SERVER['QUERY_STRING'];
	  }
	  header(sprintf("Location: %s", $insertGoTo));
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8; " />
<title>Untitled Document</title>
<link rel="stylesheet" href="../3372/styles.css" type="text/css" />
<link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
<script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
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
          <fieldset>
          <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2" enctype="multipart/form-data">
            <table align="center">
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Identification Card No:</td>
                  <td align="left"><?php echo $row_rsStudent['icNo']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Student ID:</td>
                  <td align="left"><?php echo $row_rsStudent['userID']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Password:</td>
                  <td align="left"><span id="sprypassword1">
                  <input type="password" name="userPassword" value="" size="32" id="myInput"/><input type="checkbox" onclick="myFunction()">  Show Password
                  </td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Name:</td>
                  <td align="left"><?php echo $row_rsStudent['userName']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Residential:</td>
                  <td align="left"><?php echo $row_rsStudent['userResidential']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Faculty:</td>
                  <td align="left"><?php echo $row_rsStudent['userFaculty']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Education Level:</td>
                  <td align="left"><?php echo $row_rsStudent['userEduLevel']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right" valign="top">Address:</td>
                  <td align="left"><?php echo $row_rsStudent['userAddress']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Telephone No.:</td>
                  <td align="left"><?php echo $row_rsStudent['userTel']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">License No:</td>
                  <td align="left"><span id="sprytextfield1">
                  <input type="text" name="userLicenseNo" value="" size="32" />
                  <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
                </tr>
                <tr valign="baseline">
                   <?php 
                    $timezone = date_default_timezone_get();
                    $min = date('Y-m-d', time());
                    ?>
                  <td nowrap="nowrap" align="right">License Valid Date:</td>
                  <td><input type="date" name="userLicenseValidDate" value="" size="32" min="<?php echo $min ?> /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">&nbsp;</td>
                  <td><input class="formbutton" type="submit" value="Insert record" /></td>
                </tr>
              </table>
              <input type="hidden" name="MM_insert" value="form2" />
          </form>
          </fieldset>
		</article>
	
		<article class="expanded">
		</article>

			
			<footer class="clear">
			<p>Pejabat Keselamatan | Universiti Teknikal Malaysia Melaka, Hang Tuah Jaya, 76100 Durian Tunggal, Melaka, Malaysia. 
Phone: +606-3316371   |   Fax: +606-3316344   |  hdajssadn dosjdsjdnaus dnxjskn nxosandjksanxa ndjsknxjknas dxasndjksandjkasndjsdnksndjksdnsjkndjsndsjandjadnjasdnajdnjakdnasjdnadajndasjkdasdnasjdnskjdnjk </p>
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

<script type="text/javascript">
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {minAlphaChars:1, minNumbers:1, minUpperAlphaChars:1});
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer");
</script>
</body>
</html>
<?php
mysql_free_result($rsStudent);

mysql_free_result($rsUser);
?>
