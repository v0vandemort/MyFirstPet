<?php

session_start();

if (isset($_POST["num"])) {
    $Num = $_POST["num"];
}

$Num16 = "";

$Sym16[10] = 'A';
$Sym16[11] = 'B';
$Sym16[12] = 'C';
$Sym16[13] = 'D';
$Sym16[14] = 'E';
$Sym16[15] = 'F';

while ($Num > 0) {
    $SymNew = $Num % 16;
    if ($SymNew > 9) {
        $SymNew = $Sym16[$SymNew];
    }
    $Num16 = ($SymNew) . $Num16;
    $Num = ($Num - $Num % 16) / 16;
}

$_SESSION["num16"] = $Num16;
header("Location:/index.php");
