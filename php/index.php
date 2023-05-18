<?php

session_start();
if ($_COOKIE["authCookie"] === "logged") {
    header("Location: /Personal_account.php");
}
?>
<!doctype html>
<html lang="en">
<head>
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>

<form action="Login.php" method="post">
    <label>Login</label>
    <input name="user-name" type="text">
    <label>Password</label>
    <input name="Password" type="password">
    <button type="submit">Enter to site</button>
    <?php
    if ($_SESSION['message']) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
    ?>
</form>

</body>
</html>
