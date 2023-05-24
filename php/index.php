<?php

session_start();
if ($_SESSION['message']) {
    echo $_SESSION['message'];
    unset($_SESSION['message']);
}

?>

<!doctype html>
<html lang="en">
<head>
    <title>Document</title>
</head>
<body>


<form action="Login.php" method="post">
    <label>Login</label>
    <input name="user-name" type="text">
    <label>Password</label>
    <input name="Password" type="password">
    <button type="submit">Enter to site</button>
    <?php

    if ($_COOKIE["Auth_cookie"] === "logged") {
        header("Location: /Personal_account.php");
    }

    ?>

</form>

</body>
</html>
