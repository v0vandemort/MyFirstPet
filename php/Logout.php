<?php
session_start();
setcookie('Auth_cookie', "", time() - 3600, "/");
unset($_SESSION["name"]);
header('Location: /index.php');
?>