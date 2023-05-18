<?php

session_start();
if (!isset($_COOKIE["authCookie"])) {
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

<h1 class="text-center">SERVER INFO</h1><br>

<div class="container">
    <div class="row">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Session name</th>
                <th scope="col">Session ID</th>
            </tr>
            </thead>
            <tbody>
            <?php
            while (($currentData = current($_SERVER))) {
                echo '<tr><td>' . key($_SERVER) . '</td>';
                echo '<td>' . $currentData . '</td></tr>';
                $currentData = next($_SERVER);
                echo '<tr><td>' . key($_SERVER) . '</td>';
                echo '<td>' . $currentData . '</td></tr>';
                $currentData = next($_SERVER);
            }
            ?>
            </tbody>
        </table>
    </div>

    <form action="cleaning.php">
        <button type="submit">Clean all sessions and cookies</button>
    </form>

</body>
</html>