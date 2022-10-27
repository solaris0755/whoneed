<?php
// (A) LOAD & USE PHPSPREADSHEET LIBRARY
require "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


# Create a new Xls Reader
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

// 1) 원본 파일을 읽는다.
$reader->setReadDataOnly(false);
//$sp1 = $reader->load(__DIR__.'/data/111.xlsx');
$sp1 = $reader->load(__DIR__.'/data/tax/form2.xlsx');
//////$sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
$sheet = $sp1->getActiveSheet();
$sheet->setTitle('검단');
//
//// (B) 새로운 액셀을 생성한다.
//$spreadsheet = new Spreadsheet();
//$sheet = $spreadsheet->getActiveSheet();
//
//// (D) 첫번째 시트를 카피해서 세번째 시트를 추가한다.
//for($i=1; $i<=1; $i++){
//    $ws = clone $sp1->getSheet(0);
//    $ws->setTitle("복사본 $i");
//    $spreadsheet->addSheet($ws);
//}

 
// (G) SAVE TO SERVER
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sp1);
$writer->save(__DIR__."/data/4-worksheets.xlsx");
