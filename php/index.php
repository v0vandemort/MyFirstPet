<?php

$input_file = file_get_contents('./INPUT.txt');
$numbers = explode(" ", $input_file);

$n = count($numbers);
$max = $numbers[1];
$min = $numbers[0];


for ($i = 0; $i + 1 <= $n; $i++) {
    if ((($i + 1) % 2 == 0) & ($max < $numbers[$i])) {
        $max = $numbers[$i];
    };
    if ((($i + 1) % 2 == 1) & ($min > $numbers[$i])) {
        $min = $numbers[$i];
    };
}
$sum_max_min = $max + $min;

echo($sum_max_min);

?>