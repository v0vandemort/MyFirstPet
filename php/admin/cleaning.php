<?php

session_start();

setcookie('authCookie', "", time() - 3600, "/");
$_SESSION = [];
session_destroy();
header('Location: ../index.php');
?>