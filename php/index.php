<?php

$input_file = file_get_contents('./INPUT.txt');
$abc = explode(" ", $input_file); //array of
$def = explode(" ", "+ + +"); //array of +/-

$abc[-2] = 0;
$abc[-1] = 0;

$xy[0] = "";
$xy[1] = "x";
$xy[2] = "y";


for ($i = 0; $i <= 2; $i++) {
    if ($abc[$i] == 0) {
        $def[$i] = "";
        $xy[$i] = "";
        $abc[$i] = "";
    };

    if (($abc[$i] > 0) & (($abc[$i - 2] == 0) & ($abc[$i - 1] == 0))) {
        $def[$i] = "";
    };
    if ($abc[$i] < 0) {
        $def[$i] = "-";
        $abc[$i] = abs($abc[$i]);
    };
    if ((abs($abc[$i]) == 1) & ($i > 0)) {
        $abc[$i] = "";
    };
};

$equation = $def[0] . $abc[0] . $def[1] . $abc[1] . $xy[1] . $def[2] . $abc[2] . $xy[2];

if (($abc[0] == $abc[1]) & ($abc[1] == $abc[2]) & ($abc[2] == 0)) {
    $equation = "0";
};

echo $equation;

?>