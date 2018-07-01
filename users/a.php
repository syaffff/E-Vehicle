<?php
$to = 'nazmiyakob1994@gmail.com';
$subject = 'dari locahost';
$message = 'hellooooo';
$headers = 'From: bengkel2group12@gmail.com';

if(mail($to, $subject, $message, $headers))
	echo "Email sent";
else
	echo "Fail";

?>