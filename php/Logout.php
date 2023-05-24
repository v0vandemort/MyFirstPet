<?php

setcookie('Auth_cookie', "", time() - 3600, "/");
header('Location: /index.php');
