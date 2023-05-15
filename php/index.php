<?php

session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <title>10 to 16</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>


<form action="count.php" class="input-group method mb-3" method="post">
    <input name="num" type="text" class="form-control" placeholder="Type number" aria-label="Type number"
           aria-describedby="button-addon2">
    <button class="btn btn-primary" type="submit">Convert to (16)</button>
</form>

<div class="container px-4 text-center">
    <div class="row gx-5">
        <div class="col">
            <div class="p-3">
                <?php
                if ($_SESSION['num16']) {
                    echo "Ваше число в шестнадцатеричной системе равно " . $_SESSION['num16'];
                    unset($_SESSION['num16']);
                }
                ?>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</body>
</html>