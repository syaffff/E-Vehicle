<?php require_once('../Connections/dbConn.php'); ?>
<?php require_once('../Connections/dbConnUtem.php'); ?>
<?php

include('Mail.php');

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
?>
<?php
mysql_select_db($database_dbConn, $dbConn);
$query_rsUser = "SELECT * FROM `user`";
$rsUser = mysql_query($query_rsUser, $dbConn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

mysql_select_db($database_dbConnUtem, $dbConnUtem);
$query_rsLect = "SELECT * FROM lecturer";
$rsLect = mysql_query($query_rsLect, $dbConnUtem) or die(mysql_error());
$row_rsLect = mysql_fetch_assoc($rsLect);
$totalRows_rsLect = mysql_num_rows($rsLect);

mysql_select_db($database_dbConnUtem, $dbConnUtem);
$query_rsStud = "SELECT * FROM student";
$rsStud = mysql_query($query_rsStud, $dbConnUtem) or die(mysql_error());
$row_rsStud = mysql_fetch_assoc($rsStud);
$totalRows_rsStud = mysql_num_rows($rsStud);

mysql_select_db($database_dbConnUtem, $dbConnUtem);
$query_rsCont = "SELECT * FROM contractor";
$rsCont = mysql_query($query_rsCont, $dbConnUtem) or die(mysql_error());
$row_rsCont = mysql_fetch_assoc($rsCont);
$totalRows_rsCont = mysql_num_rows($rsCont);
?>
<?php
/*
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
  $password=$_POST['password'];
  
//  $hash = sha1($password);
//  $sql_hash = mysql_real_escape_string($hash);
  
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "application.php";
  $MM_redirectLoginFailed = "login2.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_dbConn, $dbConn);
  
  $LoginRS__query=sprintf("SELECT icNo, userPassword FROM `user` WHERE icNo=%s AND userPassword=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $dbConn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) 
	{
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
	
	echo "<script type=\"text/javascript\">".
		 "alert('Invalid IC number or password. Try again');".
		 "</script>";
  }
}*/

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

if (isset($_POST['icNo'])) 
{
  $loginUsername=$_POST['icNo'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "userPosition";
  $MM_redirectLoginSuccess = "users/application.php";
  $MM_redirectLoginFailed = "login2.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_dbConn, $dbConn);
  	
  $LoginRS__query=sprintf("SELECT icNo, userPassword, userPosition FROM `user` WHERE icNo=%s AND userPassword=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $dbConn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) 
  {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'userPosition');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) 
	{
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
	if($_SESSION['MM_UserGroup']=="Admin")
		header("Location: admin/admin_home.php");
	else if ($_SESSION['MM_UserGroup']!="Admin")
    	header("Location: " . $MM_redirectLoginSuccess );
  }
  else 
  {
    header("Location: ". $MM_redirectLoginFailed );
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="UTF-8">
  <title>Sign-Up/Login Form</title>
  <link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

  
      <link rel="stylesheet" href="../sign-up-login-form/css/style.css">
      <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</head>

<body>
  <div class="form">
    <img src="../images/logoUTeMPNG.png" style="width:300px; height:145px;"/>
    <img src="../images/polis.png" style="width:150px; height:163px;"/>
      <br />
      <br />
      <ul class="tab-group">
        <li class="tab active"><a href="#signup">Register</a></li>
        <li class="tab"><a href="#login">Log In</a></li>
      </ul>
      
      <div class="tab-content">
        <div id="signup">   
          <h1></h1>
          
          <form action="" method="get" name="register">
          <div class="field-wrap">
          <label>Identification Card No<span class="req">*</span></label>
          <input type="text" name="icNo" required autocomplete="off"/></div>
          <button name="btn_search" class="button button-block"/>Get Started</button>
          
           <?php
			if(isset($_GET["btn_search"]))
			{
				$email ="";
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
					} else
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
				
				//mysqli_close($con2);
				
				//$result1 = mysqli_query($con1, $sql1);
				//$result2 = mysqli_query($con1, $sql2);
				//$result3 = mysqli_query($con1, $sql3);
				//$result4 = mysqli_query($con2, $sql4);
				
				//echo ("q1".$main_db1);
				//echo ("q2".$main_db2);
				//echo ("q3".$main_db3);
				//echo ("q4".$sticker);
				
				/*
   				$r1 = mysqli_query($con1, $sql1);
				$r2 = mysqli_query($con1, $sql2);
				$r3 = mysqli_query($con1, $sql3);
				
				$rr1 = mysqli_fetch_array($r1);
				$rr2 = mysqli_fetch_array($r2);
				$rr3 = mysqli_fetch_array($r3);
				
				*/
				
				if (($main_db1 || $main_db2 || $main_db3) && !($sticker))
				{
					$length = 10;
				    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
					$charactersLength = strlen($characters);
					$randomString = '';
					for ($i = 0; $i < $length; $i++) 
					{
						$randomString .= $characters[rand(0, $charactersLength - 1)];
					}
					
					//date_default_timezone_set("Asia/Kuala_Lumpur");
					$timezone = date_default_timezone_get();
					
					$timestamp = date('Y-M-d H:i:s', time());
					$expire = strtotime('24 hours', time());
					//$expire = date( "Y-M-d H:i:s", strtotime( $timestamp ) + 24 * 3600 );
					
					//echo $timestamp;					
					//echo $expire; 
					//date('Y-M-d H:i:s', strtotime('$timestamp+ 13 hours'));
					//echo $timezone;   
					
					$query = "SELECT * FROM url WHERE icNo='$_GET[icNo]'";
					$result = mysqli_query($con2, $query);
					
					if (mysqli_num_rows($result))
					{
						$sql = "DELETE FROM url WHERE icNo='$_GET[icNo]'";
						mysqli_query($con2, $sql);
					}
						
					$sql = "INSERT INTO url (icNo, ext, expire) VALUES ('$_GET[icNo]', '$randomString', FROM_UNIXTIME('$expire'))";
					mysqli_query($con2, $sql);
										
					$from = 'E-Sticker<bengkel2group12@gmail.com>';
					$subject = 'Email Verification';
					
					if ($main_db1)
					{
						$xx = "LL";
						$row_r1 = mysqli_fetch_assoc($result1);
					}
						
					else if ($main_db2)
					{
						$xx = "SS";
						$row_r1 = mysqli_fetch_assoc($result2);
					}
					
					else if ($main_db3)
					{
						$xx = "CC";
						$row_r1 = mysqli_fetch_assoc($result3);
					}
					
					$email = $row_r1['userEmail'];
					$to = $email;;
						
					$body = "
You are only two steps away from using the E-Sticker system. Please click the link below to verify your email address then fill in your information as needed in the form.
					
					 http://localhost/sticker/users/s.php?ext=$randomString&xx=$xx 
					 
					 
This is an automated email. Please do not reply to this email.
   ***This verification email only valid for 24 hours***
					";
					
					
					$headers = array(
						'From' => $from,
						'To' => $to,
						'Subject' => $subject
					);
					
		
					$smtp = Mail::factory('smtp', array(
							'host' => 'ssl://smtp.gmail.com',
							'port' => '465',
							'auth' => true,
							'username' => 'bengkel2group12@gmail.com',
							'password' => '@Group12@'
						));
		
					$mail = $smtp->send($to, $headers, $body);
					
					echo "<script type='text/javascript'>alert('An email has been sent')</script>";
					 
				} else if (($main_db1 || $main_db2|| $main_db3) && ($sticker))
				{					
					 echo "<script type='text/javascript'>alert('Already have an account. Please log in.')</script>";
					 
				} else
				{
					echo "<script type='text/javascript'>alert('No records')</script>";
				}
			}
			
          ?>
          
          </form>

        </div>
        
        <div id="login">   
          <h1>Welcome Back!</h1>
          
          <form ACTION="<?php echo $loginFormAction; ?>" METHOD="POST" name="login">
          
            <div class="field-wrap">
            <label>
              Identification Card No<span class="req">*</span>
            </label>
            <input type="text" name="icNo" required autocomplete="off"/>
          </div>
          
          <div class="field-wrap">
            <label>
              Password<span class="req">*</span>
            </label>
            <input type="password" name="password" required autocomplete="off"/ id="myInput">
          </div>
          
          <button class="button button-block" type="submit"/>Log In</button>
          
          </form>

        </div>
        
      </div><!-- tab-content -->
      
</div> <!-- /form -->

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

<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

<script  src="../sign-up-login-form/js/index.js"></script>

</body>
</html>
<?php
mysql_free_result($rsUser);

mysql_free_result($rsLect);

mysql_free_result($rsStud);

mysql_free_result($rsCont);
?>
