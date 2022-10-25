<?php
/**
 * 여러 현장에 인원들이 중복으로 잡혀있는지 체크하기 위한 처리
 */
include_once('./_common.php');

// Load PhpSpreadsheet library.
require_once('vendor/autoload.php');
 
 
// Import classes.
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

# Create a new Xls Reader
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();

// Tell the reader to only read the data. Ignore formatting etc.
$reader->setReadDataOnly(true);

// Read the spreadsheet file.
$spreadsheet = $reader->load(__DIR__.'/../excel_data/a.xls');

$sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
$data = $sheet->toArray();

// output the data to the console, so you can see what there is.
die(print_r($data, true));