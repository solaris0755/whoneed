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

// 2) 카피한 시트를 추가한다.
$sheet = clone $spreadsheet->getSheet(0);
$sheet->setTitle("검단");
$sheet->setCellValue("A1", "Hello World!");
// B7 : 사용일자
// C7 : 사용내역
// C5 : 현장명
// H7 : 총금액
// D21 : 은행명
// D22 : 계좌
// D23 : 예금주
$spreadsheet->addSheet($sheet,1);

// (E) DELETE WORKSHEET
$spreadsheet->removeSheetByIndex(0);

// (G) SAVE TO SERVER
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save(__DIR__."/data/검단.xlsx");
