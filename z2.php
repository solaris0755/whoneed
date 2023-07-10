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

$date_cols = array(
    1 => 'AZ',
    2 => 'BA',
    3 => 'BB',
    4 => 'BC',
    5 => 'BD',
    6 => 'BE',
    7 => 'BF',
    8 => 'BG',
    9 => 'BH',
    10 => 'BI',
    11 => 'BJ',
    12 => 'BK',
    13 => 'BL',
    14 => 'BM',
    15 => 'BN',
    16 => 'BO',
    17 => 'BP',
    18 => 'BQ',
    19 => 'BR',
    20 => 'BS',
    21 => 'BT',
    22 => 'BU',
    23 => 'BV',
    24 => 'BW',
    25 => 'BX',
    26 => 'BY',
    27 => 'BZ',
    28 => 'CA',
    29 => 'CB',
    30 => 'CC',
    31 => 'CD',
);


$arr = find_all_files(__DIR__.'/data/kim/202306');
//print_r($arr);

function work($src){
    global $date_cols;

//    echo "$src\n";
    preg_match('/\((.*)\)/', $src, $match);
//    print_r($match); die;
    $place = $match[1];
    echo "사업장 : $place\n";

    // Read the Excel file.
    $reader = IOFactory::createReader("Xlsx");
    $sp = $reader->load($src);
    $sp->setActiveSheetIndex(0);// 첫번째 시트
//    $sp->setActiveSheetIndex(1);// 두번째 시트

    for($i=0; $i<100; $i++){
        $y=5+$i*4;
        $cell = "B$y";
        $name = $sp->getActiveSheet()->getCell($cell)->getValue();
        if(!$name) break;

        $name = preg_replace('/\(.*/','', $name);
        $name = trim($name);
        if(!$name) break;

        echo "$name\n";
        for($d=1; $d<=31; $d++){
            $cell = $date_cols[$d].$y;
            $w = $sp->getActiveSheet()->getCell($cell)->getValue();
//            echo "$cell -> $w, ";
//            if($w) $w_list[]=$x;
            if( $w ){
                $sql = "insert into sal set place='$place',name='$name',w='$w',d='$d'";
                sql_query($sql);
            }
        }

        if(!$name) break;
    }
}

function find_all_files($dir) {
    $root = scandir($dir);
    foreach ($root as $value) {
        if ($value === '.' || $value === '..' || $value=='vendor') {
            continue;
        }
        if (is_file("$dir/$value")) {
            if( strstr($value,'.xlsx')){
//                echo $value.PHP_EOL;
                work("$dir/$value");
            }
            $result[] = "$dir/$value";
            continue;
        }
        foreach (find_all_files("$dir/$value") as $value) {
            $result[] = $value;
        }
    }
    return $result;
}