<?php

$InputFile = file_get_contents('./INPUT.txt');
$abc = explode(" ", $InputFile); //array of
$def = explode(" ", "+ + +"); //array of +/-

$abc[-2] = 0;
$abc[-1] = 0;

$xy[0] = "";
$xy[1] = "x";
$xy[2] = "y";

$Equation = "";

for ($i = 0; $i <= 2; $i++) {
    if ($abc[$i] == 0) {
        $def[$i] = "";
        $xy[$i] = "";
        $abc[$i] = "";
    };

    if ($abc[$i] < 0) {
        $def[$i] = "-";
        $abc[$i] = abs($abc[$i]);
    };
    if (($Equation == "") & ($abc[$i] > 0)) {
        $def[$i] = "";
    };

    if ((abs($abc[$i]) == 1) & ($i > 0)) {
        $abc[$i] = "";
    };

    $Equation .= $def[$i] . $abc[$i] . $xy[$i];
};


if ($Equation == "") {
    $Equation = "0";
};

echo $Equation;
