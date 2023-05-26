<?php

$fileInput = file_get_contents("INPUT.txt");
$fileData=explode("\n",$fileInput);

$num=$fileData[0];

$minDay=1;
$maxDay=31;

$workDays=array_slice($fileData,1);

foreach ($workDays as &$val){
    $val=explode(" ",$val);
    $val[0]=intval($val[0]);
    $val[1]=intval($val[1]);
    if ($minDay<=$val[0]){
        $minDay=$val[0];
    };
    if ($maxDay>=$val[1]){
        $maxDay=$val[1];
    };
}

if ($maxDay-$minDay<0){
    echo "NO";
}   else    {
    echo "Yes";
}
