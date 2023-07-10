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

$arr = find_all_files(__DIR__."/data/sal3");

function file_work($src) {
    preg_match('/\((.*)\)/', $src, $match);
    $place = $match[1];

    // Read the Excel file.
    $reader = IOFactory::createReader("Xlsx");
    $sp = $reader->load($src);
    $sp->setActiveSheetIndex(2);// 3번째 시트
    $sheet = $sp->getActiveSheet();

    $maxRow = $sheet->getHighestRow();
    echo "$place : $maxRow\n";
    $skip_cnt=0;
    $data = [];
    for ($i = 1; $i <= $maxRow; $i++) {
        // 계속 빈칸 나오면 중단
        if($sheet->getCell('A'.$i)->getValue()=='') {
            $skip_cnt++;
            if( $skip_cnt>=10) break;
        }
        else $skip_cnt=0;

        $v1 = $sheet->getCell('A' . $i)->getCalculatedValue();
        $v2 = $sheet->getCell('C' . $i)->getCalculatedValue();
        if(!$v1) continue;
//        echo "$v1 -> $v2\n";

        // 년월
        preg_match('/(202[2-3]{1})년\s*([0-9]{2})월/', $v1, $match);
        if ($match) {
            $ym = $match[1] . $match[2];
            $data = [];
            $data['년월']=$ym;
            $data['현장']=$place;
//            print_r($data);
//            echo $v2;
        }

        // 주민번호
        if ( $data && $v2 && preg_match('/[0-9]{6}-[0-9]{7}/', $v2)) {
            parse_gongsoo($sheet, $i, $data);
        }
    }
}
function parse_gongsoo($sheet, $y, $data){
    $z=$y+1;
    $tmp = $sheet->getCell("B$y")->getCalculatedValue();
    $tmp = str_replace("\n","", $tmp);
    $tmp = str_replace("\r","", $tmp);
    $tmp = trim($tmp);
    $data['이름'] = $tmp;
    $data['주소'] = $sheet->getCell("C$z")->getCalculatedValue();
    $data['주민번호'] = $sheet->getCell("C$y")->getCalculatedValue();
    $data['연락처'] = $sheet->getCell("D$y")->getCalculatedValue();

    $data['공수'] = $sheet->getCell("U$y")->getCalculatedValue();
    $data['일수'] = $sheet->getCell("U$z")->getCalculatedValue();
    $data['단가'] = $sheet->getCell("V$y")->getCalculatedValue();
    $data['금액'] = $sheet->getCell("W$y")->getCalculatedValue();
    $data['수당'] = $sheet->getCell("W$z")->getCalculatedValue();
    $data['합계'] = $sheet->getCell("X$y")->getCalculatedValue();
    $data['갑근세'] = $sheet->getCell("Z$y")->getCalculatedValue();
    $data['주민세'] = $sheet->getCell("AA$y")->getCalculatedValue();
    $data['고용보험'] = $sheet->getCell("AB$y")->getCalculatedValue();
    $data['국민연금'] = $sheet->getCell("Z$z")->getCalculatedValue();
    $data['건강보험'] = $sheet->getCell("AA$z")->getCalculatedValue();
    $data['장기요양보험'] = $sheet->getCell("AB$z")->getCalculatedValue();
    $data['공제합계'] = $sheet->getCell("AC$y")->getCalculatedValue();
    $data['최종합계'] = $sheet->getCell("AD$y")->getCalculatedValue();

    $data['d1'] = $sheet->getCell("E$y")->getCalculatedValue();
    $data['d2'] = $sheet->getCell("F$y")->getCalculatedValue();
    $data['d3'] = $sheet->getCell("G$y")->getCalculatedValue();
    $data['d4'] = $sheet->getCell("H$y")->getCalculatedValue();
    $data['d5'] = $sheet->getCell("I$y")->getCalculatedValue();
    $data['d6'] = $sheet->getCell("J$y")->getCalculatedValue();
    $data['d7'] = $sheet->getCell("K$y")->getCalculatedValue();
    $data['d8'] = $sheet->getCell("L$y")->getCalculatedValue();
    $data['d9'] = $sheet->getCell("M$y")->getCalculatedValue();
    $data['d10'] = $sheet->getCell("N$y")->getCalculatedValue();
    $data['d11'] = $sheet->getCell("O$y")->getCalculatedValue();
    $data['d12'] = $sheet->getCell("P$y")->getCalculatedValue();
    $data['d13'] = $sheet->getCell("Q$y")->getCalculatedValue();
    $data['d14'] = $sheet->getCell("R$y")->getCalculatedValue();
    $data['d15'] = $sheet->getCell("S$y")->getCalculatedValue();

    $data['d16'] = $sheet->getCell("E$z")->getCalculatedValue();
    $data['d17'] = $sheet->getCell("F$z")->getCalculatedValue();
    $data['d18'] = $sheet->getCell("G$z")->getCalculatedValue();
    $data['d19'] = $sheet->getCell("H$z")->getCalculatedValue();
    $data['d20'] = $sheet->getCell("I$z")->getCalculatedValue();
    $data['d21'] = $sheet->getCell("J$z")->getCalculatedValue();
    $data['d22'] = $sheet->getCell("K$z")->getCalculatedValue();
    $data['d23'] = $sheet->getCell("L$z")->getCalculatedValue();
    $data['d24'] = $sheet->getCell("M$z")->getCalculatedValue();
    $data['d25'] = $sheet->getCell("N$z")->getCalculatedValue();
    $data['d26'] = $sheet->getCell("O$z")->getCalculatedValue();
    $data['d27'] = $sheet->getCell("P$z")->getCalculatedValue();
    $data['d28'] = $sheet->getCell("Q$z")->getCalculatedValue();
    $data['d29'] = $sheet->getCell("R$z")->getCalculatedValue();
    $data['d30'] = $sheet->getCell("S$z")->getCalculatedValue();
    $data['d31'] = $sheet->getCell("T$z")->getCalculatedValue();

    db_work($data);
}

function db_work($data){
    $sql = "select mb_no from g5_emp where jumin='{$data['주민번호']}'";
    $row = sql_fetch($sql);
    if(!$row){
        $sql = "INSERT INTO g5_emp SET name='{$data['이름']}',jumin='{$data['주민번호']}', addr='{$data['주소']}', hp='{$data['연락처']}'";
        sql_query($sql);

        $mb_no = sql_insert_id();
    }else{
        $mb_no = $row['mb_no'];
    }

    $sql = <<<SQL
INSERT INTO g5_sal
SET
ym='{$data['년월']}',
place='{$data['현장']}',
mb_no=$mb_no,
일수='{$data['일수']}',
공수='{$data['공수']}',
단가='{$data['단가']}',
금액='{$data['금액']}',
수당='{$data['수당']}',
합계='{$data['합계']}',
갑근세='{$data['갑근세']}',
주민세='{$data['주민세']}',
고용보험='{$data['고용보험']}',
국민연금='{$data['국민연금']}',
건강보험='{$data['건강보험']}',
장기요양보험='{$data['장기요양보험']}',
공제합계='{$data['공제합계']}',
최종합계='{$data['최종합계']}',
d1='{$data['d1']}',
d2='{$data['d2']}',
d3='{$data['d3']}',
d4='{$data['d4']}',
d5='{$data['d5']}',
d6='{$data['d6']}',
d7='{$data['d7']}',
d8='{$data['d8']}',
d9='{$data['d9']}',
d10='{$data['d10']}',
d11='{$data['d11']}',
d12='{$data['d12']}',
d13='{$data['d13']}',
d14='{$data['d14']}',
d15='{$data['d15']}',
d16='{$data['d16']}',
d17='{$data['d17']}',
d18='{$data['d18']}',
d19='{$data['d19']}',
d20='{$data['d20']}',
d21='{$data['d21']}',
d22='{$data['d22']}',
d23='{$data['d23']}',
d24='{$data['d24']}',
d25='{$data['d25']}',
d26='{$data['d26']}',
d27='{$data['d27']}',
d28='{$data['d28']}',
d29='{$data['d29']}',
d30='{$data['d30']}',
d31='{$data['d31']}'
SQL;
//    debug_log($sql);
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