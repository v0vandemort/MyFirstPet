<?php

session_start();

if (isset($_POST["num"])) {
    $num = $_POST["num"];
}

$num16 = "";

$sym16[10] = 'A';
$sym16[11] = 'B';
$sym16[12] = 'C';
$sym16[13] = 'D';
$sym16[14] = 'E';
$sym16[15] = 'F';

while ($num > 0) {
    $symNew = $num % 16;
    if ($symNew > 9) {
        $symNew = $sym16[$symNew];
    }
    $num16 = ($symNew) . $num16;
    $num = ($num - $num % 16) / 16;
}

$_SESSION["num16"] = $num16;
header("Location:/index.php");
?>