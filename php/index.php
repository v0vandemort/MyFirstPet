<?php

$fileInput = file_get_contents('./INPUT.txt');

$fileString = explode(" ", $fileInput);


$arrayLength = count($fileString);
$exitData = "";
for ($i = 0; $i + 1 <= $arrayLength; $i++) {
    if (mb_strlen($fileString[$i]) > 7) {
        $fileString[$i] = mb_strimwidth($fileString[$i], 0, 7, "*");
    };
    $exitData .= $fileString[$i]." ";
};
echo $exitData;