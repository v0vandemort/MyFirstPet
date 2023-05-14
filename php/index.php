<?php

$fileInput = file_get_contents("Login.php");
$firstSymbol = "{";
$secondSymbol = "}";

$fileStrLen = mb_strlen($fileInput);

$checker = 0;
$left = 0;    //left и right вводятся только для показа в конце. Их можно заменить одной переменной.
$right = 0;   // тогда вместо $left++ и $right++ будут команды val++ val-- соответственно. вместо выражеения $left-$right будет использовано $val;
for ($i = 0; $i + 1 <= $fileStrLen; $i++) {
    if (mb_substr($fileInput, $i, 1) === $firstSymbol) {
        $left++;
    };
    if (mb_substr($fileInput, $i, 1) === $secondSymbol) {
        $right++;
    }
    if (($left - $right) < 0) {
        $checker++;
    }
}

switch ($left - $right) {
    case 0:
        if ($checker === 0) {
            echo "Code checked. '{' and '}' are settled in right position";
        } else {
            echo "ERROR Detected - '}' is settled before it's '{'";
        };
        break;
    case ($left - $right) < 0:
        echo "ERROR Detected - '}' doesn't have a pair";
        break;
    case ($left - $right) > 0:
        echo "ERROR Detected - '{' doesn't have a pair";
        break;
}
echo "<br>num of '{' - " . $left . ". <br> num of '}' - " . $right;