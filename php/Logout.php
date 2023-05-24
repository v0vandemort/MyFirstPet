<?php

session_start();
setcookie('authCookie', "", time() - 3600, "/");
unset($_SESSION["name"]);
header('Location: /index.php');