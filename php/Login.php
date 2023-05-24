<?php

session_start();

$Login = "admin";
$Password = "12345";
$UserName = "Нет логина";
$UserPassword = "Нет пароля";

if (isset($_POST["user-name"])) {
    $UserName = $_POST["user-name"];
};
if (isset($_POST["Password"])) {
    $UserPassword = $_POST["Password"];
};

if (($Login === $UserName) & ($Password === $UserPassword)) {
    setcookie('authCookie', 'logged', 0, '/');
    $_SESSION["message"] = 'Логин и пароль  верны';
    $_SESSION["name"] = 'Admin';
    header('Location: /Personal_account.php');
} else {
    $_SESSION["message"] = '<br>Логин и/или пароль не верны';
    header('Location: /index.php');
};

