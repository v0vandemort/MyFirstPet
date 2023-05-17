<?php
session_start();

setcookie('Auth_cookie', "", time() - 3600, "/");
session_destroy();
header('Location: /index.php');
?>