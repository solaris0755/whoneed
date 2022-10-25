<?php
include_once('./_common.php');

// Load PhpSpreadsheet library.
require_once('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

# Create a new Xls Reader
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

// Tell the reader to only read the data. Ignore formatting etc.
$reader->setReadDataOnly(true);

//==========================================
// 은행정보 로드
//==========================================
$spreadsheet = $reader->load(__DIR__.'/../excel_data/tax/bank.xlsx');
$sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
$bank_list = $sheet->toArray();
//print_r($bank_list);
/*
 *     [249] => Array
        (
            [0] => 이진성(로뎀식당)
            [1] => 352-1629-6395-43
            [2] => 농협
        )
 */

//==========================================
// 업체별 세금계산서정보 로드
//==========================================
// Read the spreadsheet file.
//$spreadsheet = $reader->load(__DIR__ . '/path/to/file.xlsx');
$spreadsheet = $reader->load(__DIR__.'/../excel_data/tax/tax1.xlsx');
$sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
$co_list = $sheet->toArray();
//print_r($list);
foreach($co_list as $row){
    /*
     *     [168] => Array
        (
            [0] => 회억리
            [1] => 프렌즈
            [2] => 44827
            [3] => 먹는샘물 외
            [4] => 258400
            [5] => 25840
            [6] => 284240
            [7] => 1
            [8] =>
        )
     */
}

