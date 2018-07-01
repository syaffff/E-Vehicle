<?php require_once('../Connections/dbConnUtem.php'); ?>
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


$colname_rsStudent = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsStudent = $_SESSION['MM_Username'];
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

				<h2><?php echo $row_rsUser['userName'] ?></h2>
				
			</header>

			<nav id="mainnav">
  				<ul>
                <li><a href="application.php">Application</a></li>
                <li><a href="summon.php">Summon</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li class="selected-item"><a href="contact.php">Contact</a></li>
                <li><a href="<?php echo $logoutAction ?>" onclick="return confirm('Are you sure to logout?');">Log Out</a></li>
           	  </ul>
			</nav>

			
			
			</aside>
			<section id="content" class="column-right">
                		
	    <article>
		  <div class="article-info"></div>

          <p></p>
           
          <p><strong>Pejabat Keselamatan</strong></p>
          <p>Universiti Teknikal Malaysia Melaka,<br />
            Hang Tuah Jaya<br />
            Durian Tunggal, 76100<br />
            Melaka, Malaysia<br />
            Tel: 06-3316362<br />
            Fax: 06-3316369</p>
          <p>&nbsp;</p>
          <p><strong>Bilik Kawalan 24 Jam</strong></p>
          <p>Tel: &nbsp;06-3316020, 012-2946020&nbsp;</p>
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
mysql_free_result($rsStudent);

mysql_free_result($rsUser);
?>
