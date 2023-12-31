<?php

session_start();

header(
    'Content-Type: text/html; charset=UTF-8'
); // Заголовок станицы с кодировкой (для корректного отображения в браузере)
mb_internal_encoding('UTF-8'); // Установка внутренней кодировки в UTF-8
mb_http_output('UTF-8'); // Установка кодировки UTF-8 входных данных HTTP-запроса
mb_http_input('UTF-8'); // Установка кодировки UTF-8 выходных данных HTTP-запроса
mb_regex_encoding('UTF-8'); // Установка кодировки UTF-8 для многобайтовых регулярных выражений

function lastLetter($city)
{
    $letter = mb_substr($city, -1);
    if (($letter === "ы") or ($letter === "ь") or ($letter === "ъ")) {
        $city = mb_substr($city, 0, -1);
        $letter = mb_substr($city, -1);
//        lastLetter($city);
    };
    return $letter;
}


$host = 'db';
$port = '5432';
$db = 'cities';
$user = 'admin';
$pass = 'example';

$dsn = "pgsql:host=$host;port=$port;dbname=$db;user=$user;password=$pass";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn);
} catch (PDOException $e) {
    echo $e->getMessage();
}


//Sending data to PGSQL table
//$query = $pdo->prepare("SELECT * FROM cities");
//$query->execute();
//$checker = $query->fetch();

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
if (isset($_COOKIE['install'])) {
//    echo "<br>База уже установлена, города уже загружены<br>";
} else {
    setcookie('install', time());
    echo "<br>База установлена, города  загружены<br>";

    $query = $pdo->prepare(
        "CREATE TABLE IF NOT EXISTS cities(
           id SERIAL PRIMARY KEY,
           name TEXT NOT NULL,
           lat REAL,
           lng REAL,
           regionId int, 
           UNIQUE (name, lat, lng))"
    );

    $query->execute();

    $query = $pdo->prepare(
        "CREATE TABLE IF NOT EXISTS regions(
           id SERIAL PRIMARY KEY,
           name TEXT NOT NULL,
           UNIQUE (name))"
    );

    $query->execute();


    $query = $pdo->prepare(
        "CREATE TABLE IF NOT EXISTS usedcities(
           id SERIAL PRIMARY KEY,
           name TEXT NOT NULL,
           UNIQUE (name))"
    );

    $query->execute();


    $fileInput = file_get_contents("tableconvert_csv_1x0a06 (1).csv");
    $fileData = explode("\n", $fileInput);

    $fileHeaders = explode(",", $fileData[0]);

    $headerKeys["id"] = "Индекс";
    $headerKeys["name"] = "Город";
    $headerKeys["lat"] = "Широта";
    $headerKeys["lng"] = "Долгота";
    $headerKeys["regionName"] = "Регион";

    foreach ($headerKeys as &$key) {
        foreach ($fileHeaders as $keyHeader => $header) {
            if (($key === $header)) {
                $key = $keyHeader;
            }
        }
    }

    foreach ($fileData as $keyRow => &$row) {
        $row = explode(",", $row);
    }

    foreach ($fileData as $row => $data) {
        if ($row === 0) {
            continue;
        }
        $arrayCities[$row][0] = $data[$headerKeys["name"]];
        $arrayCities[$row][1] = $data[$headerKeys["lat"]];
        $arrayCities[$row][2] = $data[$headerKeys["lng"]];
        $arrayCities[$row][3] = $data[$headerKeys["regionName"]];

        $query = $pdo->prepare("INSERT INTO regions (name) VALUES (:name) ");
        $query->execute(array('name' => $arrayCities[$row][3]));

        $query = $pdo->prepare("SELECT * FROM regions WHERE name=:name");
        $query->execute(['name' =>$arrayCities[$row][3]]);
        $foundedCity = $query->fetchAll();

        $arrayCities[$row][3] = $foundedCity [0][0];

    }

    echo "<pre>";
    print_r($arrayCities);
    echo "</pre>";
    foreach ($arrayCities as $row => $data) {
        if ($row === 0) {
            continue;
        }

        $name = $data[0];
        $lat = $data[1];
        $lng = $data[2];
        $regionId = $data[3];
        $dataString = array('name' => $name, 'lat' => $lat, 'lng' => $lng,'regionId' => $regionId);
        $query = $pdo->prepare("INSERT INTO cities (name, lat, lng, regionId) VALUES (:name, :lat, :lng, :regionId) ");
        $query->execute($dataString);
        if ($query->execute($dataString) !== false) {
            echo "<br>Запись не добавлена  <br><br>";
        }
    }
}

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Игра в города</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
           crossorigin="anonymous">

<!--    <script type="text/javascript">-->
<!--        $('.city').submit(function (e) {-->
<!--            e.preventDefault();-->
<!--            let th = $(this);-->
<!--            let mess = $('.mess');-->
<!--            let btn = th.find('.btn');-->
<!--            btn.addClass('progress-bar-striped progress-bar-animated')-->
<!---->
<!--            $.ajax({-->
<!---->
<!--                type: 'POST',-->
<!--                data:th.serialize(),-->
<!--                success:function () {-->
<!--                btn.removeClass('progress-bar-striped progress-bar-animated');-->
<!--                mess.html('<div class="alert alert-success">Город принят, проверяем</div>');-->
<!--            },error: function () {-->
<!--                mess.html('<div class="alert alert-success">Ошибка отправки</div>');-->
<!--                btn.removeClass('progress-bar-striped progress-bar-animated');-->
<!--            }-->
<!---->
<!--        })-->
<!--        }-->
<!--    </script>-->
</head>
<body>
<br>
<br>
<br>
<h1 align="center">Привет, игрок!</h1>

<br>
<br>
<br>
<br>
<br>

<div class="container">
    <div class="row">
        <div class="col" align="center">

        </div>
        <div class="col">
            <form id="cityPaste" class="city" onsubmit="subForm(city)" align="center" method="post">
                <div class="mb-3">
                    <label id="formForCity" align="center" for="exampleInputEmail1" class="form-label">
                        <h6 class="dialogue-container">
<!--                            --><?php
//                        if (isset($_POST["city"])) {
//                            //принимаем данные
//                            $city = mb_strtoupper(mb_substr($_POST["city"], 0, 1)) . mb_substr($_POST["city"], 1);
//
//
//                            if (!($city === "")) { //если полученное не пустая строка
//                                $_SESSION["postCity"] = $city; //храним полученный город, пока не помещаем в playerCity, так ка может такого города нет
//                                //-------------------------------
//                                echo "<br>Проверяю ваш город. <br>";
//                                $query = $pdo->prepare("SELECT name FROM cities WHERE name=:name");
//                                $query->execute(['name' => $city]);
//                                $foundedCity = $query->fetch();
//
//
//                                //                    echo "<pre>";
//                                //                    print_r($foundedCity);
//                                //                    echo "</pre>";
//
//
//                                if ((mb_strtoupper(mb_substr($_SESSION['postCity'], 0, 1)) == mb_strtoupper(
//                                            lastLetter($_SESSION['compCity'])
//                                        )) or (lastLetter($_SESSION['compCity']) === "")) {
//                                    if ($city === $foundedCity["name"]) {           ////подправить условие не видит данные с таблицы
//                                        echo "<br>Я знаю такой город<br>";
//
//                                        $query = $pdo->prepare("SELECT name FROM usedCities WHERE name=:name");
//                                        $query->execute(['name' => $foundedCity['name']]);
//                                        $usedCheck = $query->fetch();
//
//                                        if (!empty($usedCheck)) {
//                                            echo "<br>Город уже был, попробуйте другой. Вам на " . lastLetter(
//                                                    $_SESSION['compCity']
//                                                );
//                                        } else {
//                                            $_SESSION['playerCity'] = $city; //город узнали, сохраняем как город игрока и отправляем в таблицу usedCities
//                                            $query = $pdo->prepare("INSERT INTO usedcities(name) VALUES (:name) ");
//                                            $query->execute(['name' => $city]);
//
//                                            if ($_SESSION['playerCity'] === $_SESSION["postCity"]) {  //Проверка очередности хода если город последний удачный и последний названный одинаковы,то ход компьютера
//                                                $query = $pdo->prepare(
//                                                    "SELECT * FROM cities where name like '" . mb_strtoupper(
//                                                        lastLetter($_SESSION['playerCity'])
//                                                    ) . "%'"
//                                                );
//                                                $query->execute();
//                                                $foundedCity = $query->fetchAll(
//                                                    PDO::FETCH_ASSOC
//                                                ); //достаем все города на нужную букву
//
//                                                $i = -1;
//                                                do {
//                                                    $i++;
//                                                    $query = $pdo->prepare(
//                                                        "SELECT name FROM usedCities WHERE name=:name"
//                                                    );  //ищем первый подходящий город в базе использованных
//                                                    $query->execute(['name' => $foundedCity[$i]['name']]);
//                                                    $usedCity = $query->fetch();
//                                                    if (empty($foundedCity[$i])) {
//                                                        echo "Game over. You win. Last city -" . $_SESSION['playerCity'];
//                                                        $_SESSION['gameOver'] = true;
//                                                        break;
//                                                    };
//                                                } while (($foundedCity[$i]['name'] === $usedCity['name']));
//
//
////                                                while (($foundedCity[$i]['name'] === $usedCity['name'])) { //пока он есть в базе, меняем города, через i++
////                                                    $i++;
////                                                    $query = $pdo->prepare("SELECT name FROM usedCities WHERE name=:name");
////                                                    $query->execute(['name' => $foundedCity[$i]['name']]);
////                                                    $usedCity = $query->fetch();
////
////                                                    if (empty($foundedCity[$i])) {
////                                                        echo "Game over. You win. Last city -" . $_SESSION['playerCity'];
////                                                        $_SESSION['gameOver']=true;
////                                                        break;
////                                                    };
////
////                                                };
//                                                //
//                                                if (!$_SESSION['gameOver']) {
//                                                    $_SESSION['compCity'] = $foundedCity[$i]['name'];
//                                                    //                            echo "'" . mb_strtoupper(lastLetter($_SESSION['playerCity'])) . "%'";
//                                                    //                            echo "<pre>";
//                                                    //                            print_r($foundedCity);
//                                                    //                            echo "</pre>";
//                                                    $_SESSION['compCity'] = $foundedCity[$i]["name"];
//                                                    $query = $pdo->prepare(
//                                                        "INSERT INTO usedcities(name) VALUES (:name) "
//                                                    );
//                                                    $query->execute(['name' => $_SESSION['compCity']]);
//
//
//                                                    $query = $pdo->prepare(
//                                                        "SELECT * FROM cities where name like '" . mb_strtoupper(
//                                                            lastLetter($_SESSION['compCity'])
//                                                        ) . "%'"
//                                                    );  /////// Вот тут проблема, нужно Исправить запрос, не может выбрать город много echo для debug
//                                                    $query->execute();
//                                                    $foundedCity = $query->fetchAll(PDO::FETCH_ASSOC);
//
//
//                                                    $i = 0;
//                                                    $query = $pdo->prepare(
//                                                        "SELECT name FROM usedCities WHERE name=:name"
//                                                    );
//                                                    $query->execute(['name' => $foundedCity[$i]['name']]);
//                                                    $usedCity = $query->fetch();
//
//                                                    //var_dump($usedCity);
//                                                    //    echo "<br>" . "<br>" . "<br>" . "<br>" . $foundedCity[$i]['name'] . "<br>" . "<br>" . "<br>" . "<br>" . "<br>";
//                                                    //
//                                                    while ($foundedCity[$i]['name'] === $usedCity['name']) {
//                                                        $i++;
//                                                        $query = $pdo->prepare(
//                                                            "SELECT name FROM cities WHERE name=:name"
//                                                        );
//                                                        $query->execute(['name' => $foundedCity[$i]['name']]);
//                                                        $usedCity = $query->fetchAll();
//                                                        if (empty($foundedCity[$i])) {
//                                                            $_SESSION['gameOver'] = true;
//                                                            break;
//                                                        };
//                                                    };
//
//
//                                                    if (!$_SESSION['gameOver']) {
//                                                        echo "Ваш город - " . $_SESSION['playerCity'] . ". Мне на '" . mb_strtoupper(
//                                                                lastLetter($_SESSION['playerCity'])
//                                                            ) . "'" . ".<br> Мой ответ : " . $_SESSION['compCity'] . "<br>" . "Вам на '" . mb_strtoupper(
//                                                                lastLetter($_SESSION['compCity'])
//                                                            ) . "'";
//                                                    } else {
//                                                        echo "Ваш город - " . $_SESSION['playerCity'] . ". Мне на '" . mb_strtoupper(
//                                                                lastLetter($_SESSION['playerCity'])
//                                                            ) . "'" . ".<br> Мой ответ : " . $_SESSION['compCity'] . "<br>" . "But game over. Computer win. Last city - " . $_SESSION['compCity'];
//                                                    }
//                                                }
//                                            };
//                                        };
//                                    } else {
//                                        $_SESSION["message"] = "Я не знаю такой город - " . $_SESSION["postCity"] . ". Попробуйте другой<br>";
//                                        echo($_SESSION["message"]);
//                                        unset($_SESSION["message"]);
//                                    };
//                                    //-------------------------------
//
//                                } else {
//                                    echo "<br>Неверная буква. Попробуйте снова. Вам нужна " . lastLetter(
//                                            $_SESSION['compCity']
//                                        );
//                                }
//                            } else {
//                                $_SESSION["message"] = "Введите название города";
//                                echo $_SESSION["message"];
//                                unset($_SESSION["message"]);
//                            };
//                        };
//
//
//                        ?>
                        </h6>

                        <input class="form-control" name="city" id="cityInput"></label>
                    <div id="help" class="form-text">Введите город</div>
                </div>
                <button type="submit" class="btn btn-primary" id="btnSubmit">Отправить</button>


            </form>
            <br>
            <form action="restart.php">
                <button type="restart" class="btn btn-primary" id="btnRestart">Начать игру заново</button>
            </form>


        </div>

        <div class="col" align="center">
            <h4>LOGS</h4>
            <br>
            <div class="log-container"><h7>
<!--                --><?php
//                $query = $pdo->prepare("SELECT * FROM usedCities");
//                $query->execute();
//                $logs = $query->fetchAll();
//
//                if (!empty($logs)) {
//                    $logs = array_reverse($logs);
//                    foreach ($logs as $log) {
//                        echo $log['name'] . "<br>";
//                    };
//                };
//                ?>

            </h7>
            </div>
        </div>

        </div>
    </div>
</div>



<script src="js/code.jquery.com_jquery-3.6.0.min.js"></script>
<script src="js/main.jquery.js"></script>


</body>
</html>


