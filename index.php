<?php
include_once('./_common.php');
include_once('./_head.php');

$sql = "select * from g5_items";
$it_list = sql_list($sql);



//
//
//
//echo "<div style='float: left; width: 33%;'><table border=1>";
//echo "<tr><td><b>조회시간</td><td><b>제품명</td><td><b>현재고</td><td><b>단 가</td></tr>";
//foreach (array_keys($ch) as $key) {
//    $temp_arr = json_decode(curl_multi_getcontent($ch[$key]), true);
//    $temp_arr2 = json_decode(str_replace("onSale", "date\":" . $datetime2 . ",\"onSale", curl_multi_getcontent($ch[$key])), true);
//    if ($key <= 22) {
//        echo "<tr><td width=160>" . $datetime2 . "</td>";
//        echo "<td width=170>" . $sku_name[$key] . "</td>";
//    } elseif ($key <= 45) {
//        if ($key == 23) {
//            echo "</table></div>";
//            echo "<div style='float: left; width: 33%;'><table border=1>";
//            echo "<tr><td><b>조회시간</td><td><b>제품명</td><td><b>현재고</td><td><b>단 가</td></tr>";
//        }
//        echo "<tr><td width=160>" . $datetime2 . "</td>";
//        echo "<td width=170>" . $sku_name_six[$key - 23] . "</td>";
//    } else {
//        if ($key == 46) {
//            echo "</table></div>";
//            echo "<div style='float: left; width: 33%;'><table border=1>";
//            echo "<tr><td><b>조회시간</td><td><b>제품명</td><td><b>현재고</td><td><b>단 가</td></tr>";
//        }
//        echo "<tr><td width=160>" . $datetime2 . "</td>";
//        echo "<td width=170>" . $sku_name_gift_set[$key - 46] . "</td>";
//    }
//    echo "<td width=50>" . $temp_arr["data"]["amountInStock"] . "</td>";
//    echo "<td width=50>" . $temp_arr["data"]["salePrice"] . "</td></tr>";
//}
//echo "</table></div>";


include_once('./_tail.php');
?>