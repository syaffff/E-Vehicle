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

$vehicleID=$_GET['vehicleID'];


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

$pdf->Cell(40);

$pdf->Image('image/'.$vehicleID.'.png',80,150,45);

$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);
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
$pdf->Cell(70,5,'',0,0);
$pdf->Cell(20,5,'',0,1);


$pdf->Cell(185,5,"$vehicleID",0,0,'C');



$pdf->Output();
?>