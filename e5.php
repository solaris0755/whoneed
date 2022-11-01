<?php
// (A) LOAD & USE PHPSPREADSHEET LIBRARY
require "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * 은행 정보를 얻는다.
 * @return array
 * @throws \PhpOffice\PhpSpreadsheet\Exception
 * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
 */
function get_bank() {
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

    // Tell the reader to only read the data. Ignore formatting etc.
    $reader->setReadDataOnly(true);

    $spreadsheet = $reader->load(__DIR__.'/data/tax/bank.xlsx');
    $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
    $list = $sheet->toArray();
    $arr = [];
    foreach($list as $row){
//        echo $row[0].PHP_EOL;
        $shop = str_replace(" ","",$row[0]);
        if(!$shop) continue;

        $arr[$shop]=[$shop,$row[1],$row[2]];
    }
    return $arr;
}

function get_tax(){
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

    // Tell the reader to only read the data. Ignore formatting etc.
    $reader->setReadDataOnly(false);
    $spreadsheet = $reader->load(__DIR__.'/data/tax/tax1.xlsx');
    $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
    $list = $sheet->toArray();
//    print_r($list);die;
    $arr = [];
    foreach($list as $row){
        $v = [
            'shop' => str_replace(" ","",$row[1]),
            'date' => $row[2],
            'memo' => $row[3],
            'price' => str_replace(',','',trim($row[6])),
        ];
        $arr[$row[0]][]=$v;

    }
    return $arr;
}
function find_bank($shop, $addr){
    global $bank_list;

    foreach($bank_list as $k=>$v){
//        echo "shop=>[$shop]\n";
        if( strstr($k, $shop)) {
            return $bank_list[$k];
        }
    }
    echo "$addr -> $shop".PHP_EOL;
    return null;
}

$bank_list = get_bank();
//print_r($bank_list);die;
$tax_list = get_tax();
//print_r($tax_list); die;


# Create a new Xls Reader
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

// 1) 원본 파일을 읽는다.
$reader->setReadDataOnly(false);
//$spreadsheet = $reader->load(__DIR__.'/data/111.xlsx');


// 2) 카피한 시트를 추가한다.
foreach($tax_list as $addr=>$arr){
    $i=1;
    $spreadsheet = $reader->load(__DIR__.'/data/tax/form.xlsx');
    foreach($arr as $v){
        $bank_info = find_bank($v['shop'], $addr);

        $sheet = clone $spreadsheet->getSheet(0);
        $sheet->setTitle($v['shop']);
        $sheet->setCellValue("B7", $v['date']);
        $sheet->setCellValue("C7", $v['memo']);
        $sheet->setCellValue("C5", $addr);
        $sheet->setCellValue("H7", $v['price']);
        if( $bank_info){
            $sheet->setCellValue("D21", $bank_info[2]);
            $sheet->setCellValue("D22", $bank_info[1]);
            $sheet->setCellValue("D23", $bank_info[0]);
        }

        $spreadsheet->addSheet($sheet,$i);

        unset($shee);
        $i++;
    }
    // (E) DELETE WORKSHEET
    $spreadsheet->removeSheetByIndex(0);

// (G) SAVE TO SERVER
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save(__DIR__."/data/result/$addr.xlsx");

    unset($spreadsheet);
    unset($writer);
}



