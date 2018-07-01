<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
include('../Mail-1.4.1/Mail/Mail.php');
$recipients = 'B031510298@student.utem.edu.my'; //CHANGE
$headers['From']= 'syfqhyhyh@gmail.com'; //CHANGE
$headers['To']= 'B031510298@student.utem.edu.my'; //CHANGE
$headers['Subject'] = 'TEST';
$body = 'hello'; // Define SMTP Parameters
$params['host'] = 'mail.authsmtp.com';
$params['port'] = '25';
$params['auth'] = 'PLAIN';
$params['username'] = 'USERNAME'; //CHANGE
$params['password'] = 'PASSWORD'; //CHANGE

/* The following option enables SMTP debugging and will print the SMTP conversation to the page, it will only help with authentication issues, if PEAR::Mail is not installed you won't get this far. */

$params['debug'] = 'true'; // Create the mail object using the Mail::factory method
$mail_object =& Mail::factory('smtp', $params); // Print the parameters you are using to the page

foreach ($params as $p){
 echo "$p<br />";
}

// Send the message
$mail_object->send($recipients, $headers, $body);

?>

</body>
</html>