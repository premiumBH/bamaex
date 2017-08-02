<?php


define('FPDF_FONTPATH','font/');
require_once('fpdf/fpdf.php'); 
require_once('fpdf/fpdi.php'); 
$pdf =& new FPDI();
$pdf->AddPage();

$sender_account_number ='123457689670';
$sender_ref ='57689670';
$sender_name = 'Ali Raza';
$sender_phone_no = '0345405598';
$sender_company = 'Avanza Solution';
$sender_address = 'Lahore Office';
$sender_city = 'Lahore Office';
$sender_country = 'Pakistan';
$sender_province = 'Punjab';
$sender_postal_code = '54000';

$receiver_account_number ='123457689670';
$receiver_ref ='57689670';
$receiver_name = 'Ali Raza';
$receiver_phone_no = '0345405598';
$receiver_company = 'Avanza Solution';
$receiver_address = 'Lahore Office';
$receiver_city = 'Lahore Office';
$receiver_country = 'Pakistan';
$receiver_province = 'Punjab';
$receiver_postal_code = '54000';

$send_creation_date = '12/24/2016';
$origin = 'BD';
$destination = 'BD';
$tracking_number = '2139874';
$no_of_packages = '5';
$value = '173.8 AED';
$receive_date = '27/63/2017';



 //Set the source PDF file
$pagecount = $pdf->setSourceFile("doc1.pdf");
//Import the first page of the file
$tpl = $pdf->importPage(1);
//Use this page as template
$pdf->useTemplate($tpl);
#Print Hello World at the bottom of the page
//Go to 1.5 cm from bottom
$pdf->SetY(36);
$pdf->SetX(1);
//Select Arial italic 8
$pdf->SetFont('Arial','B',8);
//Print centered cell with a text in it
$pdf->Cell(30, 10, $sender_account_number, 0, 0, 'C');
$pdf->Cell(90, 10, $sender_ref, 0, 0, 'C');

$pdf->SetY(46);
$pdf->Cell(5, 10, $sender_name, 0, 0, 'C');
$pdf->Cell(130, 10, $sender_phone_no, 0, 0, 'C');

$pdf->SetY(55);
$pdf->Cell(15, 10, $sender_company, 0, 0, 'C');

$pdf->SetY(65);
$pdf->Cell(11, 10, $sender_address, 0, 0, 'C');

$pdf->SetY(77);
$pdf->Cell(23, 10, $sender_city, 0, 0, 'C');
$pdf->Cell(119, 10, $sender_province, 0, 0, 'C');

$pdf->SetY(88);
$pdf->Cell(23, 10, $sender_country, 0, 0, 'C');
$pdf->Cell(119, 10, $sender_postal_code, 0, 0, 'C');

$pdf->SetY(104);
$pdf->Cell(10, 10, $receiver_account_number, 0, 0, 'C');
$pdf->Cell(105, 10, $receiver_ref, 0, 0, 'C');

$pdf->SetY(114);
$pdf->Cell(6, 10, $receiver_name, 0, 0, 'C');
$pdf->Cell(110, 10, $receiver_phone_no, 0, 0, 'C');

$pdf->SetY(122);
$pdf->Cell(15, 10, $receiver_company, 0, 0, 'C');

$pdf->SetY(132);
$pdf->Cell(11, 10, $receiver_address, 0, 0, 'C');

$pdf->SetY(144);
$pdf->Cell(23, 10, $receiver_city, 0, 0, 'C');
$pdf->Cell(119, 10, $receiver_province, 0, 0, 'C');

$pdf->SetY(155);
$pdf->Cell(23, 10, $receiver_country, 0, 0, 'C');
$pdf->Cell(119, 10, $receiver_postal_code, 0, 0, 'C');

$pdf->SetY(172);
$pdf->Cell(150, 10, $send_creation_date, 0, 0, 'C');

$pdf->SetY(31);
$pdf->Cell(248, 10, $origin, 0, 0, 'C');


$pdf->SetY(31);
$pdf->Cell(344, 10, $destination, 0, 0, 'C');

$pdf->SetY(39);
$pdf->Cell(280, 10, $tracking_number, 0, 0, 'C');

$pdf->SetY(63);
$pdf->Cell(220, 10, $no_of_packages, 0, 0, 'C');


$pdf->SetY(94);
$pdf->Cell(330, 10, $value, 0, 0, 'C');

$pdf->SetY(173);
$pdf->Cell(349, 10, $receive_date, 0, 0, 'C');


$pdf->Image('logo.png',10,6,30,'PNG');
$pdf->Image('image.gif',170,6,30,'GIF');

//$pdf->SetY(10);
$pdf->Image('confirm.png',136,48,3,5,'PNG');
$pdf->Image('confirm.png',163,48,3,5,'PNG');

$pdf->Image('confirm.png',132,72,3,5,'PNG');
$pdf->Image('confirm.png',155,72,3,5,'PNG');

$pdf->Image('confirm.png',111,112,3,5,'PNG');
$pdf->Image('confirm.png',150,112,3,5,'PNG');

$pdf->Image('confirm.png',111,121,3,5,'PNG');
$pdf->Image('confirm.png',150,121,3,5,'PNG');

$pdf->Image('confirm.png',111,131,3,5,'PNG');
$pdf->Image('confirm.png',150,130,3,5,'PNG');

$pdf->Image('confirm.png',111,141,3,5,'PNG');



$pdf->Output("my_modified_pdf.pdf", "F");