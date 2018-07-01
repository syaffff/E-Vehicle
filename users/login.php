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

mysql_select_db($database_dbConn, $dbConn);
$query_rs_user = "SELECT * FROM `user`";
$rs_user = mysql_query($query_rs_user, $dbConn) or die(mysql_error());
$row_rs_user = mysql_fetch_assoc($rs_user);
$totalRows_rs_user = mysql_num_rows($rs_user);

mysql_select_db($database_dbConnUtem, $dbConnUtem);
$query_rs_student = "SELECT * FROM student";
$rs_student = mysql_query($query_rs_student, $dbConnUtem) or die(mysql_error());
$row_rs_student = mysql_fetch_assoc($rs_student);
$totalRows_rs_student = mysql_num_rows($rs_student);

mysql_select_db($database_dbConnUtem, $dbConnUtem);
$query_rs_lecturer = "SELECT * FROM lecturer";
$rs_lecturer = mysql_query($query_rs_lecturer, $dbConnUtem) or die(mysql_error());
$row_rs_lecturer = mysql_fetch_assoc($rs_lecturer);
$totalRows_rs_lecturer = mysql_num_rows($rs_lecturer);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['icNo'])) {
  $loginUsername=$_POST['icNo'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "application_cont.php?icNo=$loginUsername";
  $MM_redirectLoginFailed = "main.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_dbConnUtem, $dbConnUtem);
  
  $LoginRS__query=sprintf("SELECT icNo FROM contractor WHERE icNo=%s",
    GetSQLValueString($loginUsername, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $dbConnUtem) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html>
<html>
<head>
<link href="../css/layout.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Login</title>
</head>

<body>
<blockquote>
<blockquote>
<blockquote>
<blockquote>
<blockquote>
<blockquote>
<blockquote>
<blockquote>
<blockquote>
<blockquote>
<blockquote>
<blockquote>
<blockquote>
<blockquote>
<hl>
  <h1>&nbsp;</h1>
  <h1>&nbsp;</h1>
  <h1>Login</h1>
  <p>&nbsp;</p>
</hl>
</blockquote>
<form ACTION="<?php echo $loginFormAction; ?>" METHOD="POST">
<label>IC No:<br/></label>
<input name="icNo" type="text" required>
<br/>
<label>Password:<br/></label>
<input type="password" name="password" required><br/>
<input type="submit" value="Login">
</form>
</blockquote>
</blockquote>
</blockquote>
</blockquote>
</blockquote>
</blockquote>
</blockquote>
</blockquote>
</blockquote>
</blockquote>
</blockquote>
</blockquote>
</blockquote>
</body>
</html>


<?php
mysql_free_result($rs_user);

mysql_free_result($rs_student);

mysql_free_result($rs_lecturer);
?>
