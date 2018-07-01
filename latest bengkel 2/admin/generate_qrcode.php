<?php


header("Content-Type: image/png");
require "vendor/autoload.php";

use Endroid\QrCode\QrCode;

//$qrcodename=$_GET['text'];

$vehilceID=$_GET['text'];

$qrcode = new QrCode($_GET['text']);
 
echo $qrcode->writeString();


if ($qrcode->writeString()) 
{

	
	$qrcode->writeFile('C:/xampp/htdocs/sticker/admin/image/'.$vehilceID.'.png');

}


die();


?>