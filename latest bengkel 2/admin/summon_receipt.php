<?php

include('../Connections/dbcode.php');
require ('fpdf18/fpdf.php');

class PDF extends FPDF
{
	function Header ()
	{
		$this->SetFont('Arial','B',15);

		//dummmy cell to put logo
		$this->Cell(20);


		// logo polis
		$this->Image('icon/polis.png',15,15,45);
		$this->Cell(30);
		//logo Utem
		//$this->Image('icon/utem.png',0,0,100);

		//text
		$this->Cell(100,10,'Summon Receipt',0,1,'C');

	}
}

$fineID=$_GET['fineID'];
$searchdata=$_GET['icNo'];


$icNo = mysqli_query($conn,'SELECT u.* , o.*, s.*, f.* FROM user u , owner o, summon s, fine f WHERE u.icNo LIKE "%'.$searchdata.'%" and s.fineID = "'.$fineID.'" and u.icNo=s.icNo and o.vehicleID= s.vehicleID and o.icNo=s.icNo and f.fineID=s.fineID');

$row = mysqli_fetch_array($icNo);


$pdf = new PDF('P', 'mm', 'A4');

$pdf->AddPage();

$pdf->SetFont('Arial','B',14);

//Cell(width, height, text, border(0:contineu, 1:new line), endline, [align](optional) )
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);

$pdf->Cell(70,5,'Pejabat Keselamatan',0,0);
$pdf->Cell(20,5,'',0,1);


$pdf->SetFont('Arial','',12);

$pdf->Cell(70,5,'Universiti Teknikal Malaysia Melaka,',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'Hang Tuah Jaya,',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'Durian Tunggal, 76100,',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'Melaka, Malaysia,',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'Tel: 06-3316362,',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'Fax: 06-3316369',0,0);
$pdf->Cell(20,5,'',0,1);

$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);

$pdf->SetFont('Arial','B',14);

$pdf->Cell(70,5,'Bilik Kawalan 24 Jam',0,0);
$pdf->Cell(20,5,'',0,1);

$pdf->SetFont('Arial','',12);

$pdf->Cell(70,5,'Tel:06-3316020, 012-2946020',0,0);
$pdf->Cell(20,5,'',0,1);

//dummy space
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);


$pdf->SetFont('Arial','B',15);

$pdf->Cell(182,5,'Summon & Payment Details',0,0,'C');
$pdf->Cell(90,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);

$pdf->SetFont('Arial','B',12);


$pdf->Cell(70,5,'FINE ID:',1,0,'J');
$pdf->Cell(90,5,$row['fineID'],1,1);
$pdf->Cell(70,5,'IDENTITY CARD NUMBER(NRIC):',1,0);
$pdf->Cell(90,5,$row['icNo'],1,1);
$pdf->Cell(70,5,'FULLNAME:',1,0);
$pdf->Cell(90,5,$row['userName'],1,1);
$pdf->Cell(70,5,'FINE TYPE:',1,0);
$pdf->Cell(90,5,$row['fineType'],1,1);
$pdf->Cell(70,5,'FINE PLACE:',1,0);
$pdf->Cell(90,5,$row['finePlace'],1,1);
$pdf->Cell(70,5,'VEHICLE PLATE NUMBER:',1,0);
$pdf->Cell(90,5, $row['vehicleID'],1,1);
$pdf->Cell(70,5,'FINE PAYMENT:',1,0);
$pdf->Cell(7,5,'RM',0,0);
$pdf->Cell(6,5,$row['finePayment'],0,0);
$pdf->Cell(77,5,'.00',0,0);
$pdf->Cell(0.05,5,'',1,1);

$pdf->Cell(70,5,'FINE STATUS:',1,0);
$pdf->Cell(90,5,$row['fineStatus'],1,1);
$pdf->Cell(70,5,'DATE PAID:',1,0);
$pdf->Cell(90,5,$row['date_paid'],1,1);


$pdf->SetFont('Arial','',12);

$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);



$pdf->SetFont('Arial','B',12);

$pdf->Cell(40,5,'Owner Signature:',0,0,'C');
$pdf->Cell(140,5,'Approve By:',0,1,'R');
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1); 

$pdf->Cell(70,5,'__________________',0,0);
$pdf->Cell(120,5,'__________________',0,1,'R');
$pdf->Cell(40,5,'Date:',0,0);
$pdf->Cell(118,5,'Date:',0,1,'R');







$pdf->Output();
?>