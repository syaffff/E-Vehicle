<?php require_once('Connections/dbConn.php'); ?>
<?php require_once('Connections/dbConnUtem.php'); ?>
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="UTF-8">
  <title>Sign-Up/Login Form</title>
  <link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

  
      <link rel="stylesheet" href="sign-up-login-form/css/style.css">
      <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</head>

<body>
  <div class="form">
    <img src="images/logoUTeMPNG.png" style="width:300px; height:145px;"/>
    <img src="images/polis.png" style="width:150px; height:163px;"/>
      <br />
      <br />
      
      <h1>Enter your IC number to reset your password</h1>
      
      <div class="tab-content">
          <h1></h1>
          
          <form action="" method="get" name="register">
          <div class="field-wrap">
          <label>Identification Card No<span class="req">*</span></label>
          <input type="text" name="icNo" required autocomplete="off"/></div>
          <button name="btn_search" class="button button-block"/>Next</button>
          
           <?php
			if(isset($_GET["icNo"]) && isset($_GET["btn_search"]))
			{
				$ic = $_GET["icNo"];
				$con = mysqli_connect('localhost', 'root', '', 'utemsticker');
				$sql = "SELECT * FROM user WHERE icNo=$ic";
				
				$result = mysqli_query($con, $sql);
				$row = mysqli_fetch_assoc($result);
				$count = mysqli_num_rows($result);
				
				if ($count)
				{
					$code = rand(1000,100000);
			
					$email = $row['userEmail'];
					$to = $email;;
					$from = 'E-Sticker<bengkel2group12@gmail.com>';
					$subject = 'Password reset';
					$body = 
					
					"This is an automated email. Please do not reply to this email.
					
					
					Your new password is = $code
					
***Please change your password immediately.***
					"
					;
					
					
					$query = "UPDATE user SET userPassword= '$code' WHERE icNo='$ic'";
					mysqli_query($con, $query);
					
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
					?>
					<script>
					alert('An email containing your new password has been sent to your email.');
					window.location.href='login2.php';
					</script>
                    
		<?php	}
				else
				{
					 echo "<script type=\"text/javascript\">".
						  "alert('Given IC number is not associated with any account.');".
			              "</script>";
				}
				
			}
			
          ?>
          
          </form>
        
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

<script  src="sign-up-login-form/js/index.js"></script>

</body>
</html>
<?php
mysql_free_result($rsUser);
?>
