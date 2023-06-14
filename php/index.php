<?php

$fileInput = file_get_contents("tableconvert_csv_1x0a06 (1).csv");
$fileData = explode("\n", $fileInput);

$fileHeaders = explode(",", $fileData[0]);

$headerKeys["id"] = "Индекс";
$headerKeys["name"] = "Город";
$headerKeys["lat"] = "Широта";
$headerKeys["lng"] = "Долгота";

foreach ($headerKeys as &$key) {
    foreach ($fileHeaders as $keyHeader => &$header) {
        if (($key === $header)) {
            $key = $keyHeader;
        }
    }
}
foreach ($fileData as $keyRow => &$row) {
    $row = explode(",", $row);
}

echo "<pre>";
print_r($fileHeaders);
echo "<pre>";
echo "<pre>";
print_r($headerKeys);
echo "<pre>";

echo "<pre>";
print_r($fileData);
echo "<pre>";
//
//foreach ($fileData as $keyRow => &$row) {
//    foreach ($headerKeys as $key => $val) {
//        pg_copy_from($db,$cities,$row);
//    }
//}
