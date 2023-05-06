<?php

$input_file = file_get_contents('./INPUT.TXT');

$numbers = explode(" ", $input_file);

$mod_1 = $numbers[0] % $numbers[1];

file_put_contents('./OUTPUT.TXT', $mod_1);
?>