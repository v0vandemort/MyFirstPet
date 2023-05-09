<?php

session_start();
if ($_SESSION['message']) {
    echo $_SESSION['message'] . "<br>";
    unset($_SESSION['message']);
};
if ($_COOKIE["Auth_cookie"]) {
    echo "Logged";
} else {
    header('Location: /index.php');
};

?>
<!doctype html>
<html lang="en">
<head>
    <title>Document</title>
</head>
<body>


<form action="Logout.php">
    <button type="submit">Logout</button>
    <!--    -->

</form>
</body>
</html>