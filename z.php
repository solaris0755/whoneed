<?php
 
// Load PhpSpreadsheet library.
require_once('vendor/autoload.php');
 
 
// Import classes.
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;


$arr = find_all_files(__DIR__);
//print_r($arr);

function to_csv($src){

    // Read the Excel file.
    $reader = IOFactory::createReader("Xlsx");
    $spreadsheet = $reader->load($src);

    // Export to CSV file.
    $writer = IOFactory::createWriter($spreadsheet, "Csv");
    $writer->setSheetIndex(0);   // Select which sheet to export.
    $writer->setDelimiter(';');  // Set delimiter.

    $dest = str_replace('.xlsx','.csv', $src);

    $writer->save($dest);

    $data = file_get_contents($dest);
    $data = str_replace('"','', $data);
    $data = str_replace(',','', $data);
    $data = str_replace('-','', $data);
    $data = str_replace(';',',', $data);
    file_put_contents($dest, $data);
}

function find_all_files($dir) {
    $root = scandir($dir);
    foreach ($root as $value) {
        if ($value === '.' || $value === '..' || $value=='vendor') {
            continue;
        }
        if (is_file("$dir/$value")) {
            if( strstr($value,'.xlsx')){
                echo $value.PHP_EOL;
                to_csv("$dir/$value");
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