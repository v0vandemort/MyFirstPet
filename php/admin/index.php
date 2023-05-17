<?php

session_start();
if (!isset($_COOKIE["Auth_cookie"])) {
    header('Location: ../index.php');
} else {
    echo '<h1 class="text-center">Admin logged</h1><br>';
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


var_dump($_SESSION);


?>
<div class="container">
    <div class="row">
        <div class="col">
            <?php


            echo session_name();


            ?>
        </div>
        <div class="col">
            <?php


            echo session_id();

            ?>
        </div>
    </div>

    <form action="cleaning.php">
        <button type="submit">Clean all sessions and cookies</button>
    </form>


</body>
</html>