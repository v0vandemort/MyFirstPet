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
            while ($currentSession = current($_SESSION)) {
                echo '<tr><td>' . key($_SESSION) . '</td>';
                echo '<td>' . $currentSession . '</td></tr>';
                $currentSession = next($_SESSION);
            }

            ?>
            <tr>
                <td><?php
                    echo session_name(); ?></td>
                <td><?php
                    echo session_id(); ?></td>
            </tr>
            <thead>
            <tr>
                <th scope="col">Cookie name</th>
                <th scope="col">Cookie data</th>
            </tr>
            </thead>
            <?php
            while ($currentCookie = current($_COOKIE)) {
                echo '<tr><td>' . key($_COOKIE) . '</td>';
                echo '<td>' . $currentCookie . '</td></tr>';
                $currentCookie = next($_COOKIE);
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