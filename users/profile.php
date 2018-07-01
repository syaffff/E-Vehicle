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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE `user` SET userPassword=%s, userResidential=%s, userAddress=%s, userTel=%s, userLicenseValidDate=%s WHERE icNo=%s",
                       GetSQLValueString($_POST['userPassword'], "text"),
					   GetSQLValueString($_POST['userResidential'], "text"),
                       GetSQLValueString($_POST['userAddress'], "text"),
                       GetSQLValueString($_POST['userTel'], "text"),
                       GetSQLValueString($_POST['userLicenseValidDate'], "date"),
                       GetSQLValueString($_SESSION['MM_Username'], "text"));

  mysql_select_db($database_dbConn, $dbConn);
  $Result1 = mysql_query($updateSQL, $dbConn) or die(mysql_error());
  
  if ($Result1)
  { ?>
  	  <script>
	  alert('Profile updated.');
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
$query_rsUser = sprintf("SELECT * FROM `user` WHERE icNo = %s", GetSQLValueString($colname_rsUser, "text"));
$rsUser = mysql_query($query_rsUser, $dbConn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8; " />
<title>Untitled Document</title>
<link rel="stylesheet" href="../3372/styles.css" type="text/css" />
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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
                <li><a href="summon.php">Summon</a></li>
                <li class="selected-item"><a href="profile.php">Profile</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="<?php echo $logoutAction ?>" onClick="return confirm('Are you sure to logout?');">Log Out</a></li>
           	  </ul>
			</nav>

			
			
			</aside>
			<section id="content" class="column-right">
                		
	    <article>
		  <div class="article-info"></div>
		  <fieldset>
          <legend>Your Profile</legend>
          <p>&nbsp;</p>
          <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
            <table align="center">
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">Identification Card No:</td>
                <td align="left"><?php echo $row_rsUser['icNo']; ?></td>
              </tr>
              <?php if ($row_rsUser['userID'])
			  { ?>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">ID:</td>
                <td><?php echo $row_rsUser['userID']; ?></td>
              </tr>
              <?php } ?>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">Password:</td>
                <td><input type="password" name="userPassword" value="<?php echo $row_rsUser['userPassword']; ?>" size="32" id="myInput"/>
           		  <input type="checkbox" onClick="myFunction()">  Show Password
                </td>
              </tr>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">Name:</td>
                <td><?php echo $row_rsUser['userName']; ?></td>
              </tr>
              <?php if ($row_rsUser['userResidential'])
			  { ?>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">Residential:</td>
                 <td><select name="userResidential">
                  <option value='<?php echo $row_rsUser['userResidential']?>' selected='selected'><?php echo $row_rsUser['userResidential']?></option>
                  <option value="None Resident" <?php if (!(strcmp("None Resident", ""))) {echo "SELECTED";} ?>>None Resident</option>
                  <option value="Lestari" <?php if (!(strcmp("Lestari", ""))) {echo "SELECTED";} ?>>Lestari</option>
                  <option value="Seri Utama" <?php if (!(strcmp("Seri Utama", ""))) {echo "SELECTED";} ?>>Seri Utama</option>
                  <option value="Satria" <?php if (!(strcmp("Satria", ""))) {echo "SELECTED";} ?>>Satria</option>
                  </select></td>
              </tr>
              <?php } ?>
              <?php if ($row_rsUser['userFaculty'])
			  { ?>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">Faculty:</td>
                <td><?php echo $row_rsUser['userFaculty']; ?></td>
              </tr>
              <?php } ?>
              <?php if ($row_rsUser['userEduLevel'])
			  { ?>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">Edu Level:</td>
                <td><?php echo $row_rsUser['userEduLevel']; ?></td>
              </tr>
              <?php } ?>
              <tr valign="baseline">
                <td align="right" valign="middle" nowrap="nowrap">Address:</td>
                <td><textarea name="userAddress" cols="32"><?php echo $row_rsUser['userAddress']; ?></textarea></td>
              </tr>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">Telephone No:</td>
                <td><input type="text" name="userTel" value="<?php echo $row_rsUser['userTel']; ?>" size="32" /></td>
              </tr>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">License No:</td>
                <td><?php echo $row_rsUser['userLicenseNo']; ?></td>
              </tr>
              <tr valign="baseline">
              	<?php 
				$timezone = date_default_timezone_get();
				$min = date('Y-m-d', time());
				?>
                <td nowrap="nowrap" align="right">License Valid Date:</td>
                <td><input type="date" name="userLicenseValidDate" value="<?php echo $row_rsUser['userLicenseValidDate']; ?>" size="32" min="<?php echo $min ?>"/></td>
              </tr>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">Position:</td>
                <td><?php echo $row_rsUser['userPosition']; ?></td>
              </tr>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">&nbsp;</td>
                <td><input class="formbutton" type="submit" value="Update record" /></td>
              </tr>
            </table>
            <input type="hidden" name="MM_update" value="form1" />
            <input type="hidden" name="icNo" value="<?php echo $row_rsUser['icNo']; ?>" />
          </form>
          </fieldset>
		</article>

<br />
<br />
<br />
<br />
<br />
		
			
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
    var x = document.getElementById("myInput");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
</script>

<?php
mysql_free_result($rsUser);
?>
