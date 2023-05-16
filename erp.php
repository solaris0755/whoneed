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

//$arr = find_all_files(__DIR__."/data/erp");
//file_read_work(__DIR__.'/data/erp/001.xls');
file_write_work();

function file_write_work(){
    $place_map = [
        '대전갑천 트리풀시티'=>'대전갑천',
        '시티오씨엘 1단지'=>'씨티오엘1단지',
        '우리월드로지텍 물류창고'=>'우리원드로지스텍물류창고',
        '이천 마장면 회억리 물류센터'=>'이천회억리물류PC',
        '이천성곡물류PC'=>'이천고백리',
        '인스파이어스탠드'=>'인스파이어스텐드',
        '인천검단 물류센터'=>'인천검단물류센터',
        '장경간합성보 목업PC'=>'장견간합석보목업PC',
        '청주 SK뷰 지하주차장'=>'청주SK뷰지하주차장',
        '표교리물류PC'=>'표고리물류PC',
        '하이닉스M15 WWT PC'=>'하이닉스M15WWT',
        '하이닉스 전력 인프라'=>'하이닉스전력인프라',
    ];

    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $reader->setReadDataOnly(false);
    $sp = $reader->load(__DIR__.'/data/erp.xlsx');

    $sql = <<<SQL
select place, ca1,ym, sum(price) tot
from erp_price
where ym<'202301'
group by place, ca1,ym
order by 1,2,3
SQL;
    $list = sql_list($sql);
    foreach($list as $row){
        // 시트찾기
        $place = $row['place'];
        $v=@$place_map[$place];
        if( $v ) $place=$v;

        $sheet = $sp->getSheetByName($place);
        if(!$sheet) echo "Sheet:$place Not Found!!\n";

        // 셀찾기
        $cell = find_cell($row);

        // 셀에 값 쓰기
        $sheet->setCellValue($cell, $row['tot']);
    }

    // (G) SAVE TO SERVER
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sp);
    $writer->save(__DIR__."/data/erp_work.xlsx");
}


function file_read_work($src){
    // Read the Excel file.
    $reader = IOFactory::createReader("Xls");
    $sp = $reader->load($src);
    sheet_work($sp, "일용직급여");//잡금
    sheet_work($sp, "인건부대비용");

//    sheet_work($sp, "원재료비");//원재료비
//    sheet_work($sp, "노무비");//노무비
//    sheet_work($sp, "일용직급여");// 급여
//    sheet_work($sp, "상여금");//상여금
//    sheet_work($sp, "임금");//임금
//    sheet_work($sp, "퇴직급여충당금전입");//퇴직급여충당금전입

}
function sheet_work($sp, $sheet_name){
    $sheet = $sp->getSheetByName($sheet_name);
    if(!$sheet) return;
    $place = $sheet->getCell("A2")->getCalculatedValue();
    $i=1;
    while(1) {
        $i++;
        $title = $sheet->getCell("B$i")->getCalculatedValue();
        $ymd = $sheet->getCell("C$i")->getCalculatedValue();
        if( $title && !$ymd ) continue;
        while (1) {
            $i++;
            $v = $sheet->getCell("C$i")->getCalculatedValue();
            if ($v == '월계') {
                $sum = $sheet->getCell("E$i")->getCalculatedValue();
                if(!$sum) $sum = $sheet->getCell("F$i")->getCalculatedValue();

                list($yy, $mm, $dd) = explode('/', $ymd);
                echo "$sheet_name > $place > $title > $yy/$mm = $sum\n";
                $data = [
                    'ca1'=>$sheet_name,
                    'ca2'=>$title,
                    'place'=>$place,
                    'ym'=>$yy.$mm,
                    'price'=>$sum
                ];
                db_work($data);
                break;
            }
            if (!$v) return;
        }
    }
}


function db_work($data){
    extract($data);
    $sql = "INSERT ignore INTO erp_price SET ca1='$ca1',ca2='$ca2',place='$place',ym='$ym',price=$price";
    sql_query($sql);
}

function find_all_files($dir) {
    $root = scandir($dir);
    foreach ($root as $value) {
        if ($value === '.' || $value === '..' || $value=='vendor') {
            continue;
        }
        if (is_file("$dir/$value")) {
            if( strstr($value,'.xls')){
                echo "=========".$value.PHP_EOL;
                file_read_work("$dir/$value");
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


function find_cell($row){
    $x_map = [
        1=>'E',
        2=>'H',
        3=>'K',
        4=>'N',
        5=>'Q',
        6=>'T',
        7=>'W',
        8=>'Z',
        9=>'AC',
        10=>'AF',
        11=>'AI',
        12=>'AL',
    ];
    $y_map = [
        '일용직급여' => 12,
        '인건부대비용'=> 10,
    ];
    $ym=$row['ym'];
    $ca=$row['ca1'];
    $y = $y_map[$ca];
    $m = substr($ym,4,2);
    $m = intval($m);
    $x = $x_map[$m];

    $cell = $x.$y;

    return $cell;
}