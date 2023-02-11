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

$ym='202201';
$arr = find_all_files(__DIR__."/data/kim/$ym");
//print_r($arr);

function file_work($src){
    global $ym;

//    echo "$src\n";
    preg_match('/\((.*)\)/', $src, $match);
//    print_r($match); die;
    $place = $match[1];
    echo "사업장 : $place\n";

    // Read the Excel file.
    $reader = IOFactory::createReader("Xlsx");
    $sp = $reader->load($src);
//    $sp->setActiveSheetIndex(0);// 첫번째 시트
    $sp->setActiveSheetIndex(1);// 두번째 시트

    for($i=0; $i<100; $i++){
        $data = [];
        $y=5+$i*2;
        $z=$y+1;

        $data['이름'] = trim($sp->getActiveSheet()->getCell("B$y")->getCalculatedValue());
        if( !$data['이름']) break;

        $data['주소'] = $sp->getActiveSheet()->getCell("C$z")->getCalculatedValue();
        $data['주민번호'] = $sp->getActiveSheet()->getCell("C$y")->getCalculatedValue();
        $data['연락처'] = $sp->getActiveSheet()->getCell("D$y")->getCalculatedValue();

        $data['현장'] = $place;
        $data['년월'] = $ym;
        $data['공수'] = $sp->getActiveSheet()->getCell("U$y")->getCalculatedValue();
        $data['일수'] = $sp->getActiveSheet()->getCell("U$z")->getCalculatedValue();
        $data['단가'] = $sp->getActiveSheet()->getCell("V$y")->getCalculatedValue();
        $data['지급'] = $sp->getActiveSheet()->getCell("X$y")->getCalculatedValue();
        $data['갑근세'] = $sp->getActiveSheet()->getCell("Z$y")->getCalculatedValue();
        $data['주민세'] = $sp->getActiveSheet()->getCell("AA$y")->getCalculatedValue();
        $data['고용보험'] = $sp->getActiveSheet()->getCell("AB$y")->getCalculatedValue();
        $data['국민연금'] = $sp->getActiveSheet()->getCell("Z$z")->getCalculatedValue();
        $data['건강보험'] = $sp->getActiveSheet()->getCell("AA$z")->getCalculatedValue();
        $data['장기요양보험'] = $sp->getActiveSheet()->getCell("AB$z")->getCalculatedValue();
        $data['실지급'] = $sp->getActiveSheet()->getCell("AD$y")->getCalculatedValue();

        db_work($data);
    }
}

function db_work($data){
    print_r($data);
    /*
    [이름] => 정창현
    [주민번호] => 경기도 안산시 상록구 장화3길 1-1 A동302호
    [주소] => 670108-1019038
    [연락처] => 010-9917-8735

    [현장] => 충북음성
    [년월] => 202201
    [공수] => 14
    [일수] => 13
    [단가] => 200000
    [지급] => 2800000
    [갑근세] => 22950
    [주민세] => 2230
    [고용보험] => 22400
    [국민연금] => 126000
    [건강보험] => 96040
    [장기요양보험] => 11060
    [실지급] => 2519320
     */

    $sql = "select num from emp where jumin='{$data['주민번호']}'";
    $row = sql_fetch($sql);
    if(!$row){
        $sql = "INSERT ignore INTO emp SET name='{$data['이름']}',jumin='{$data['주민번호']}', addr='{$data['주소']}', hp='{$data['연락처']}'";
        sql_query($sql);

        $mb_no = sql_insert_id();
    }else{
        $mb_no = $row['num'];
    }

    $sql = <<<SQL
INSERT INTO salary 
SET
ym='{$data['년월']}',
place='{$data['현장']}',
mb_no=$mb_no,
gongsoo='{$data['공수']}',
danga='{$data['단가']}',
sal='{$data['지급']}',
gab='{$data['갑근세']}',
ju='{$data['주민세']}',
go='{$data['고용보험']}',
kuk='{$data['국민연금']}',
health='{$data['건강보험']}',
jang='{$data['장기요양보험']}',
realsal='{$data['실지급']}'
SQL;
    debug_log($sql);
    sql_query($sql);

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
                file_work("$dir/$value");
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