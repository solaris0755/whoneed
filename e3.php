<?php
// (A) LOAD & USE PHPSPREADSHEET LIBRARY
require "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


# Create a new Xls Reader
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

// 1) 원본 파일을 읽는다.
$reader->setReadDataOnly(false);
//$spreadsheet = $reader->load(__DIR__.'/data/111.xlsx');
$spreadsheet = $reader->load(__DIR__.'/data/tax/form.xlsx');
//////$sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
//$sheet = $spreadsheet->getActiveSheet();
//$sheet->setTitle('검단');

$sheet = clone $spreadsheet->getSheet(0);
$sheet->setTitle("검단");
$spreadsheet->addSheet($sheet,1);

// (E) DELETE WORKSHEET
$spreadsheet->removeSheetByIndex(0);

// (G) SAVE TO SERVER
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save(__DIR__."/data/검단.xlsx");
