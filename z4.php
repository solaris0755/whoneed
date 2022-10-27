<?php
include_once('./_common.php');

// Load PhpSpreadsheet library.
require_once('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

// https://code-boxx.com/phpspreadsheet-beginner-tutorial/


# Create a new Xls Reader
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

// Tell the reader to only read the data. Ignore formatting etc.
$reader->setReadDataOnly(true);

//==========================================
// 은행정보 로드
//==========================================
//$spreadsheet = $reader->load(__DIR__.'/data/tax/bank.xlsx');
//$sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
//$bank_list = $sheet->toArray();
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
//$spreadsheet = $reader->load(__DIR__.'/data/tax/tax1.xlsx');
//$sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
//$co_list = $sheet->toArray();
////print_r($list);
//foreach($co_list as $row){
//    /*
//     *     [168] => Array
//        (
//            [0] => 회억리
//            [1] => 프렌즈
//            [2] => 44827
//            [3] => 먹는샘물 외
//            [4] => 258400
//            [5] => 25840
//            [6] => 284240
//            [7] => 1
//            [8] =>
//        )
//     */
//}

//==========================================
// form
//==========================================
// https://code-boxx.com/phpspreadsheet-beginner-tutorial/
// https://phpspreadsheet.readthedocs.io/en/latest/topics/worksheets/
$spreadsheet = $reader->load(__DIR__.'/data/tax/form.xlsx');
//////$sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
$clonedWorksheet = clone $spreadsheet->getSheet(0);
$clonedWorksheet->setTitle("복사본");
//$spreadsheet->addSheet($clonedWorksheet);
//$spreadsheet->removeSheetByIndex(0);

$new_sp = new Spreadsheet();
$new_sp->createSheet();

// Create a new worksheet called "My Data"
//new Worksheet\Worksheet();

$myWorkSheet = new Worksheet($new_sp, 'My Data');

// Attach the "My Data" worksheet as the first worksheet in the Spreadsheet object
//$new_sp->addSheet($myWorkSheet, 0);
$new_sp->addExternalSheet($clonedWorksheet);

// SAVE TO SERVER
$writer = new Xlsx($new_sp);
$writer->save(__DIR__."/data/zzz.xlsx");

//$arr = $sheet->toArray();
//print_r($arr);

// TODO :
// 1) 시트를 카피해서 다른 파일에 쓰기
// 2) 스타일까지 포함하여 시트를 카피해보기