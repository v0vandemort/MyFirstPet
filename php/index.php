<?php

$FileInput = file_get_contents('./INPUT.txt');

$FileString = explode(" ", $FileInput);


$ArrayLength = count($FileString);
$ExitData = "";
for ($i = 0; $i + 1 <= $ArrayLength; $i++) {
    if (mb_strlen($FileString[$i]) > 7) {
        $FileString[$i] = mb_strimwidth($FileString[$i], 0, 7, "*");
    };
    $ExitData .= $FileString[$i]." ";
};
echo $ExitData;