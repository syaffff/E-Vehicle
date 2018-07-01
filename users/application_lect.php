<?php require_once('../Connections/dbConn.php'); ?>
<?php require_once('../Connections/dbConnUtem.php'); ?>
<?php

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
$_SESSION['MM_Username'] = "";

$colname_rsUrl = "-1";
if (isset($_GET['ext'])) {
  $colname_rsUrl = $_GET['ext'];
}

mysql_select_db($database_dbConn, $dbConn);
$query_rsUrl = sprintf("SELECT * FROM url WHERE ext = %s", GetSQLValueString($colname_rsUrl, "text"));
$rsUrl = mysql_query($query_rsUrl, $dbConn) or die(mysql_error());
$row_rsUrl = mysql_fetch_assoc($rsUrl);
$totalRows_rsUrl = mysql_num_rows($rsUrl);

$_SESSION['MM_Username'] = $row_rsUrl['icNo'];


mysql_select_db($database_dbConnUtem, $dbConnUtem);
$query_rsLecturer = sprintf("SELECT * FROM lecturer WHERE icNo = %s", GetSQLValueString($_SESSION['MM_Username'], "text"));
$rsLecturer = mysql_query($query_rsLecturer, $dbConnUtem) or die(mysql_error());
$row_rsLecturer = mysql_fetch_assoc($rsLecturer);
$totalRows_rsLecturer = mysql_num_rows($rsLecturer);


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1") && (isset($_FILES['icFile'])) && 
(isset($_FILES['licenseFile'])) && (isset($_FILES['cardFile'])))  
{
	
	$types = array('image/jpeg', 'image/png');
	 
	if (in_array($_FILES['icFile']['type'], $types) || in_array($_FILES['licenseFile']['type'], $types) || in_array($_FILES['cardFile']['type'], $types)) 
	{
		$check1 = getimagesize($_FILES["icFile"]["tmp_name"]);
		$check2 = getimagesize($_FILES["licenseFile"]["tmp_name"]);
		$check3 = getimagesize($_FILES["cardFile"]["tmp_name"]);
		
		if(($check1 !== false) && ($check2 !== false) && ($check3 !== false))
		{
			$image1 = $_FILES['icFile']['tmp_name'];
			$image2 = $_FILES['licenseFile']['tmp_name'];
			$image3 = $_FILES['cardFile']['tmp_name'];
			
			
			$imgContent1 = addslashes(file_get_contents($image1));
			$imgContent2 = addslashes(file_get_contents($image2));
			$imgContent3 = addslashes(file_get_contents($image3));
	
			$sql = "INSERT INTO user (icNo, userID, userPassword, userName, userFaculty, userAddress, userTel, userLicenseNo, userLicenseValidDate, userPosition, icFile, licenseFile, cardFile) VALUES ('$row_rsLecturer[icNo]', '$row_rsLecturer[userID]', '$_POST[userPassword]', '$row_rsLecturer[userName]', '$row_rsLecturer[userFaculty]', '$row_rsLecturer[userAddress]', '$row_rsLecturer[userTel]', '$_POST[userLicenseNo]', '$_POST[userLicenseValidDate]', 'Lecturer', '$imgContent1', '$imgContent2', '$imgContent3')"; 
	
			mysql_select_db($database_dbConn, $dbConn);
			$Result1 = mysql_query($sql, $dbConn) or die(mysql_error());
		
			 if ($Result1)
		  { ?>
			  <script>
              alert('Registration completed. Please login again');
              window.location.href='../login.php';
              </script>
<?php	  }
		}
	}else 
  { ?>
  	  <script>
	  alert('Wrong photo format.');
	  window.location.href='profile.php';
	  </script>
<?php
  }
}

$colname_rsUser = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUser = $_SESSION['MM_Username'];
}
mysql_select_db($database_dbConn, $dbConn);
$query_rsUser = sprintf("SELECT * FROM user WHERE icNo = %s", GetSQLValueString($_SESSION['MM_Username'], "text"));
$rsUser = mysql_query($query_rsUser, $dbConn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

/*
$colname_rsLecturer = "-1";
if (isset($_GET['icNo'])) {
  $colname_rsLecturer = $_GET['icNo'];
}
*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8; " />
<title>E-Sticker</title>
<link rel="stylesheet" href="../3372/styles.css" type="text/css" />
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
</head>

<body>

		<section id="body" class="width">
<aside id="sidebar" class="column-left">

			<header>
				<h1><a href="#">UTeM</a></h1>

				<h2><?php echo $row_rsLecturer['userName']; ?></h2>
				
			</header>

			<nav id="mainnav">
  				<ul>
                <li class="selected-item"><a href="#">Registration</a></li>
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
            <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" enctype="multipart/form-data">
              <table align="center">
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Identification Card No:</td>
                  <td><?php echo $row_rsLecturer['icNo']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Staff ID:</td>
                  <td><?php echo $row_rsLecturer['userID']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Password:</td>
                  <td><input type="password" name="userPassword" value="" size="32" id="myInput" />
                  	  <input type="checkbox" onClick="myFunction()">  Show Password
                  </td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Name:</td>
                  <td><?php echo $row_rsLecturer['userName']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Faculty:</td>
                  <td><?php echo $row_rsLecturer['userFaculty']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Address:</td>
                  <td><?php echo $row_rsLecturer['userAddress']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Telephone No.:</td>
                  <td><?php echo $row_rsLecturer['userTel']; ?></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">License No:</td>
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
                  <td nowrap="nowrap" align="right">Copy of Identification Card:</td>
                  <td><input type="file" name="icFile" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Copy of Driving License:</td>
                  <td><input type="file" name="licenseFile" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">Copy of Staff Card:</td>
                  <td><input type="file" name="cardFile" value="" size="32" /></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap="nowrap" align="right">&nbsp;</td>
                  <td><input class="formbutton" type="submit" value="Insert record" /></td>
                </tr>
              </table>
              <input type="hidden" name="MM_insert" value="form1" />
            </form>
          </fieldset>
		</article>
        
        <p><i>**Only JPG, JPEG and PNF file format<br />
			 **Save filename as icNumber_filename.format<br />
			 Example: 901111011111_ic.jpg, 901111011111_license.jpg, 901111011111_card.jpg<br />
         </i></p>
        
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
	

</body>
</html>
<?php
mysql_free_result($rsUser);

mysql_free_result($rsLecturer);
?>
