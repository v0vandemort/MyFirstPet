<?php

$FileInput = file_get_contents("Login.php");
$FirstSymbol = "{";
$SecondSymbol = "}";

$FileStrLen = mb_strlen($FileInput);

$Checker = 0;
$Left = 0;    //left и right вводятся только для показа в конце. Их можно заменить одной переменной.
$Right = 0;   // тогда вместо $Left++ и $Right++ будут команды val++ val-- соответственно. вместо выражеения $Left-$Right будет использовано $val;
for ($i = 0; $i + 1 <= $FileStrLen; $i++) {
    if (mb_substr($FileInput, $i, 1) === $FirstSymbol) {
        $Left++;
    };
    if (mb_substr($FileInput, $i, 1) === $SecondSymbol) {
        $Right++;
    }
    if (($Left - $Right) < 0) {
        $Checker++;
    }
}

switch ($Left - $Right) {
    case 0:
        if ($Checker === 0) {
            echo "Code checked. '{' and '}' are settled in right position";
        } else {
            echo "ERROR Detected - '}' is settled before it's '{'";
        };
        break;
    case ($Left - $Right) < 0:
        echo "ERROR Detected - '}' doesn't have a pair";
        break;
    case ($Left - $Right) > 0:
        echo "ERROR Detected - '{' doesn't have a pair";
        break;
}
echo "<br>num of '{' - " . $Left . ". <br> num of '}' - " . $Right;