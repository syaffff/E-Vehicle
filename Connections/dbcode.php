<?php

$conn = mysqli_connect("localhost","root","","utemsticker");

if(!$conn){
	die("Connection Failed:".mysqli_connect_error());
}

