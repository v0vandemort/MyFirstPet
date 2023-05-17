<?php

session_start();

$login = "admin";
$password = "12345";
$userName = "Нет логина";
$userPassword = "Нет пароля";


if (isset($_POST["user-name"])) {
    $userName = $_POST["user-name"];
};
if (isset($_POST["Password"])) {
    $userPassword = $_POST["Password"];
};

if (($login === $userName) & ($password === $userPassword)) {
    setcookie('Auth_cookie', 'logged', 0, '/');
    $_SESSION["message"] = 'Логин и пароль  верны';
    $_SESSION["name"] = 'Admin';
    header('Location: /Personal_account.php');
} else {
    $_SESSION["message"] = 'Логин и/или пароль не верны';
    header('Location: /index.php');
};

?>
