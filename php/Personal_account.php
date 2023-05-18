<?php

session_start();
if (!isset($_COOKIE["authCookie"])) {
    header('Location: /index.php');
} else {
    echo "Logged";
};
?>

<!doctype html>
<html lang="en">
<head>
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>

<?php
if ($_SESSION['message']) {
    echo "<br>" . $_SESSION['message'] . "<br>";
    unset($_SESSION['message']);
};
?>

<form action="Logout.php">
    <button type="submit">LogOut</button>
</form>

</body>
</html>