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
    echo "Чекер не пуст";
    echo "База уже установлена, города уже загружены";
} else {
    setcookie('install', time());
    echo "База  установлена, города  загружены";
    echo "Cookie INSTALL NOT FOUND";
    $query = $pdo->prepare(
        "CREATE TABLE IF NOT EXISTS cities(
           id SERIAL PRIMARY KEY,
           name TEXT NOT NULL,
           lat REAL,
           lng REAL,
           UNIQUE (name, lat, lng))"
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

    foreach ($headerKeys as &$key) {
        foreach ($fileHeaders as $keyHeader => &$header) {
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
    }

    foreach ($arrayCities as $row => $data) {
        if ($row === 0) {
            continue;
        }

        $name = $data[0];
        $lat = $data[1];
        $lng = $data[2];
        $dataString = array('name' => $name, 'lat' => $lat, 'lng' => $lng);
        $query = $pdo->prepare("INSERT INTO cities (name, lat, lng) VALUES (:name, :lat, :lng) ");
        $query->execute($dataString);
        if ($query->execute($dataString) !== false) {
            echo "Запись не добавлена  <br><br>";
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
          integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
<h1 align="center">Привет игрок!</h1>


<form method="post">
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">
            <?php
            if (isset($_POST["city"])) {
                //принимаем данные
                $city = mb_strtoupper(mb_substr($_POST["city"], 0, 1)) . mb_substr($_POST["city"], 1, null);
                echo mb_strtoupper(mb_substr($_SESSION['postCity'], 0, 1));
                echo lastLetter($_SESSION['compCity']);

                if (!($city === "")) {
                    $_SESSION["postCity"] = $city; //храним полученный город, пока не помещаем в playerCity, так ка может такого города нет
                    //-------------------------------
                    echo "Приняты не пустые данные";
                    $query = $pdo->prepare("SELECT name FROM cities WHERE name=:name");
                    $query->execute(['name' => $city]);
                    $foundedCity = $query->fetch();


                    echo "<pre>";
                    print_r($foundedCity);
                    echo "</pre>";


                    if ((mb_strtoupper(mb_substr($_SESSION['postCity'], 0, 1)) == mb_strtoupper(
                                lastLetter($_SESSION['compCity'])
                            )) or (lastLetter($_SESSION['compCity']) === "")) {
                        if ($city === $foundedCity["name"]) {           ////подправить условие не видит данные с таблицы
                            echo "Я знаю такой город<br>";

                            $query = $pdo->prepare("SELECT name FROM usedCities WHERE name=:name");
                            $query->execute(['name' => $foundedCity['name']]);
                            $usedCheck = $query->fetch();

                            if (!empty($usedCheck)) {
                                echo "Город уже был, попробуйте другой. Вам на " . lastLetter($_SESSION['compCity']);
                            } else {
                                $_SESSION['playerCity'] = $city; //город узнали, сохраняем как город игрока и отправляем в таблицу usedCities, проверку по этой таблице еще не добавлял
                                $query = $pdo->prepare("INSERT INTO usedcities(name) VALUES (:name) ");
                                $query->execute(['name' => $city]);
                            }
                        } else {
                            $_SESSION["message"] = "Я не знаю такой город - " . $_SESSION["postCity"] . ". Попробуйте другой<br>";
                            echo($_SESSION["message"]);
                            unset($_SESSION["message"]);
                        };
                        //-------------------------------
                        if ($_SESSION['playerCity'] === $_SESSION["postCity"]) {
                            $query = $pdo->prepare(
                                "SELECT * FROM cities where name like '" . mb_strtoupper(
                                    lastLetter($_SESSION['playerCity'])
                                ) . "%'"
                            );  /////// Вот тут проблема, нужно Исправить запрос, не может выбрать город много echo для debug
                            $query->execute();
                            $foundedCity = $query->fetchAll(PDO::FETCH_ASSOC);


                            $i = 0;
                            $query = $pdo->prepare("SELECT name FROM usedCities WHERE name=:name");
                            $query->execute(['name' => $foundedCity[$i]['name']]);
                            $usedCity = $query->fetch();
                            var_dump($usedCity);
                            echo "<br>" . "<br>" . "<br>" . "<br>" . $foundedCity[$i]['name'] . "<br>" . "<br>" . "<br>" . "<br>" . "<br>";
                            while ($foundedCity[$i]['name'] === $usedCity['name']) {
                                $i++;
                                $query = $pdo->prepare("SELECT name FROM cities WHERE name=:name");
                                $query->execute(['name' => $foundedCity[$i]['name']]);
                                $usedCity = $query->fetchAll();
                            };
                            $_SESSION['compCity'] = $foundedCity[$i]['name'];
                            echo "'" . mb_strtoupper(lastLetter($_SESSION['playerCity'])) . "%'";
                            echo "<pre>";
                            print_r($foundedCity);
                            echo "</pre>";
                            $_SESSION['compCity'] = $foundedCity[$i]["name"];

                            $query = $pdo->prepare("INSERT INTO usedcities(name) VALUES (:name) ");
                            $query->execute(['name' => $_SESSION['compCity']]);

                            echo "Ваш город - " . $_SESSION['playerCity'] . ". Мне на '" . mb_strtoupper(
                                    lastLetter($_SESSION['playerCity'])
                                ) . "'" . ".<br> Мой ответ : " . $_SESSION['compCity'] . "<br>" . "Вам на '" . mb_strtoupper(
                                    lastLetter($_SESSION['compCity'])
                                ) . "'";
                        };
                    } else {
                        echo "Неверная буква. Попробуйте снова. Вам нужна " . lastLetter($_SESSION['compCity']);
                    }
                } else {
                    $_SESSION["message"] = "Введите название города";
                    echo $_SESSION["message"];
                    unset($_SESSION["message"]);
                };
            };


            ?>

            <input class="form-control" name="city"></label>
        <div id="help" class="form-text">Введите город</div>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</body>
</html>