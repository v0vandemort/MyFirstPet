<?php

session_start();

$login = "admin";
$password = "12345";
$user_name = "Нет логина";
$user_password = "Нет пароля";


if (isset($_POST["user-name"])) {
    $user_name = $_POST["user-name"];
};
if (isset($_POST["Password"])) {
    $user_password = $_POST["Password"];
};

if (($login === $user_name) & ($password === $user_password)) {
    setcookie('Auth_cookie', 'logged', 0, '/');
    $_SESSION["message"] = 'Логин и пароль  верны';
    header('Location: /Personal_account.php');
} else {
    $_SESSION["message"] = 'Логин и/или пароль не верны';
    header('Location: /index.php');
};

?>
