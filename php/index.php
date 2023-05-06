<?php

$input_file = file_get_contents("./INPUT.txt");


$number_n = (int)$input_file;


function count_fact($l, $m)
{
    if ($l > $m) {
        return "1";
    };
    if ($l == $m) {
        return $l;
    };
    if ($m - $l == 1) {
        return $m * $l;
    } else {
        $j = intdiv($l + $m, 2);
        $a1 = count_fact($l, $j);
        $a2 = count_fact($j + 1, $m);
        return $a1 * $a2;
    }
}

if ($number_n >= 0) {
    if ($number_n >= 1000) {
        echo 'N - is more than 1000 or equal';
    } else {
        $factorial = count_fact(2, $number_n);
        $output_file = file_put_contents("./output.txt", "$factorial");
    }
} else {
    echo 'N - is less than ZERO';
}

?>