<?php

$fileInput = file_get_contents("tableconvert_csv_1x0a06 (1).csv");
$fileData=explode("\n",$fileInput);

$fileHeaders=explode(",",$fileData[0]);

$headerKeys["id"]="Индекс";
$headerKeys["name"]="Город";
$headerKeys["lat"]="Широта";
$headerKeys["lng"]="Долгота";

echo "<pre>";
print_r($fileHeaders);
echo "<pre>";

echo $fileHeaders[0];

foreach ($headerKeys as &$key){
    foreach ($fileHeaders as $keyHeader => $header){
        if ($key===$header){
            $key=$keyHeader;
        }
    }
}

echo "<pre>";
print_r($fileHeaders);
echo "<pre>";
echo "<pre>";
print_r($headerKeys);
echo "<pre>";