<?php
// (A) LOAD & USE PHPSPREADSHEET LIBRARY
require "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 
// (B) 첫번째 시트의 셀에 글자를 쓴다.
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("First Sheet");
$sheet->setCellValue("A1", "Hello World!");
 
// (C) 두번째 시트를 추가한다.
$spreadsheet->createSheet();
 
// (C1) WORKSHEETS ARE IN RUNNING SEQUENCE NUMBER - 0, 1, 2, ...
$sheet = $spreadsheet->getSheet(1);
 
// (C2) ALTERNATIVELY, WE CAN GET BY NAME (AFTER WE SET THE TITLE)
//$sheet = $spreadsheet->getSheetByName("TITLE");
 
// (C3) SET WORKSHEET TITLE + CELL VALUE
$sheet->setTitle("Second Sheet");
$sheet->setCellValue("A1", "Foo Bar!");
 
// (D) 첫번째 시트를 카피해서 세번째 시트를 추가한다.
$evilClone = clone $spreadsheet->getSheet(0);
$evilClone->setTitle("Evil Clone");
$spreadsheet->addSheet($evilClone);
 
// (E) DELETE WORKSHEET
// $spreadsheet->removeSheetByIndex(0);
 
// (F) GET TOTAL NUMBER OF WORKSHEETS
// $total = $spreadsheet->getSheetCount();
 
// (G) SAVE TO SERVER
$writer = new Xlsx($spreadsheet);
$writer->save(__DIR__."/data/4-worksheets.xlsx");
