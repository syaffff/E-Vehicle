<?php require_once('../Connections/dbConn.php'); ?>
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

$ext = "-1";
$xx = "-1";
if (isset($_GET['ext']) && isset($_GET['xx'])) {
  $ext = $_GET['ext'];
  $xx = $_GET['xx'];
}
mysql_select_db($database_dbConn, $dbConn);
$query_rsUrl = sprintf("SELECT * FROM url WHERE ext = %s", GetSQLValueString($ext, "text"));
$rsUrl = mysql_query($query_rsUrl, $dbConn) or die(mysql_error());
$row_rsUrl = mysql_fetch_assoc($rsUrl);
$totalRows_rsUrl = mysql_num_rows($rsUrl);
 

$timezone = date_default_timezone_get();

if(time() <= strtotime($row_rsUrl['expire']) )
{ 
	if($xx == "SS")
	{ 
		header("Location: application_stud.php?ext=$ext");
		
    } else if ($xx == "LL")
	{ 
		header("Location: application_lect.php?ext=$ext");
    
	} else if ($xx === "CC")
	{
		header("Location: application_cont.php?ext=$ext");
	}
}
else 
{
	echo "Link already expired! Do register again.";
}


mysql_free_result($rsUrl);
?>
