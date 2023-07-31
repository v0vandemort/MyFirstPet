<?php

session_start();

header('Content-Type: text/html; charset=UTF-8'); // Заголовок станицы с кодировкой (для корректного отображения в браузере)
mb_internal_encoding('UTF-8'); // Установка внутренней кодировки в UTF-8
mb_http_output('UTF-8'); // Установка кодировки UTF-8 входных данных HTTP-запроса
mb_http_input('UTF-8'); // Установка кодировки UTF-8 выходных данных HTTP-запроса
mb_regex_encoding('UTF-8'); // Установка кодировки UTF-8 для многобайтовых регулярных выражений


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
    $dialogueMess = $e->getMessage();
}
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
$dialogueData = "Doesnt set";
$divData = array();
if (isset($_POST["city"])) {
    //принимаем данные
    $city = mb_strtoupper(mb_substr($_POST["city"], 0, 1)) . mb_substr($_POST["city"], 1);


    if (!($city === "")) { //если полученное не пустая строка
        $_SESSION["postCity"] = $city; //храним полученный город, пока не помещаем в playerCity, так ка может такого города нет
        //-------------------------------
        $dialogueMess = "<br>Проверяю ваш город. <br>";
        $query = $pdo->prepare("SELECT name FROM cities WHERE name=:name");
        $query->execute(['name' => $city]);
        $foundedCity = $query->fetch();


        //                    echo "<pre>";
        //                    print_r($foundedCity);
        //                    echo "</pre>";


        if ((mb_strtoupper(mb_substr($_SESSION['postCity'], 0, 1)) == mb_strtoupper(
                    lastLetter($_SESSION['compCity'])
                )) or (lastLetter($_SESSION['compCity']) === "")) {
            if ($city === $foundedCity["name"]) {           ////подправить условие не видит данные с таблицы
                $dialogueMess .= "<br>Я знаю такой город<br>";

                $query = $pdo->prepare("SELECT name FROM usedCities WHERE name=:name");
                $query->execute(['name' => $foundedCity['name']]);
                $usedCheck = $query->fetch();

                if (!empty($usedCheck)) {
                    $dialogueMess .= "<br>Город уже был, попробуйте другой. Вам на " . lastLetter(
                            $_SESSION['compCity']
                        );
                } else {
                    $_SESSION['playerCity'] = $city; //город узнали, сохраняем как город игрока и отправляем в таблицу usedCities
                    $query = $pdo->prepare("INSERT INTO usedcities(name) VALUES (:name) ");
                    $query->execute(['name' => $city]);

                    if ($_SESSION['playerCity'] === $_SESSION["postCity"]) {  //Проверка очередности хода если город последний удачный и последний названный одинаковы,то ход компьютера
                        $query = $pdo->prepare(
                            "SELECT * FROM cities where name like '" . mb_strtoupper(
                                lastLetter($_SESSION['playerCity'])
                            ) . "%'"
                        );
                        $query->execute();
                        $foundedCity = $query->fetchAll(
                            PDO::FETCH_ASSOC
                        ); //достаем все города на нужную букву

                        $i = -1;
                        do {
                            $i++;
                            $query = $pdo->prepare(
                                "SELECT name FROM usedCities WHERE name=:name"
                            );  //ищем первый подходящий город в базе использованных
                            $query->execute(['name' => $foundedCity[$i]['name']]);
                            $usedCity = $query->fetch();
                            if (empty($foundedCity[$i])) {
                                $dialogueMess .= "Game over. You win. Last city -" . $_SESSION['playerCity'];
                                $_SESSION['gameOver'] = true;
                                break;
                            };
                        } while (($foundedCity[$i]['name'] === $usedCity['name']));


//                                                while (($foundedCity[$i]['name'] === $usedCity['name'])) { //пока он есть в базе, меняем города, через i++
//                                                    $i++;
//                                                    $query = $pdo->prepare("SELECT name FROM usedCities WHERE name=:name");
//                                                    $query->execute(['name' => $foundedCity[$i]['name']]);
//                                                    $usedCity = $query->fetch();
//
//                                                    if (empty($foundedCity[$i])) {
//                                                        echo "Game over. You win. Last city -" . $_SESSION['playerCity'];
//                                                        $_SESSION['gameOver']=true;
//                                                        break;
//                                                    };
//
//                                                };
                        //
                        if (!$_SESSION['gameOver']) {
                            $_SESSION['compCity'] = $foundedCity[$i]['name'];
                            //                            echo "'" . mb_strtoupper(lastLetter($_SESSION['playerCity'])) . "%'";
                            //                            echo "<pre>";
                            //                            print_r($foundedCity);
                            //                            echo "</pre>";
                            $_SESSION['compCity'] = $foundedCity[$i]["name"];
                            $query = $pdo->prepare(
                                "INSERT INTO usedcities(name) VALUES (:name) "
                            );
                            $query->execute(['name' => $_SESSION['compCity']]);


                            $query = $pdo->prepare(
                                "SELECT * FROM cities where name like '" . mb_strtoupper(
                                    lastLetter($_SESSION['compCity'])
                                ) . "%'"
                            );  /////// Вот тут проблема, нужно Исправить запрос, не может выбрать город много echo для debug
                            $query->execute();
                            $foundedCity = $query->fetchAll(PDO::FETCH_ASSOC);


                            $i = 0;
                            $query = $pdo->prepare(
                                "SELECT name FROM usedCities WHERE name=:name"
                            );
                            $query->execute(['name' => $foundedCity[$i]['name']]);
                            $usedCity = $query->fetch();

                            //var_dump($usedCity);
                            //    echo "<br>" . "<br>" . "<br>" . "<br>" . $foundedCity[$i]['name'] . "<br>" . "<br>" . "<br>" . "<br>" . "<br>";
                            //
                            while ($foundedCity[$i]['name'] === $usedCity['name']) {
                                $i++;
                                $query = $pdo->prepare(
                                    "SELECT name FROM cities WHERE name=:name"
                                );
                                $query->execute(['name' => $foundedCity[$i]['name']]);
                                $usedCity = $query->fetchAll();
                                if (empty($foundedCity[$i])) {
                                    $_SESSION['gameOver'] = true;
                                    break;
                                };
                            };


                            if (!$_SESSION['gameOver']) {
                                $dialogueMess .= "Ваш город - " . $_SESSION['playerCity'] . ". Мне на '" . mb_strtoupper(
                                        lastLetter($_SESSION['playerCity'])
                                    ) . "'" . ".<br> Мой ответ : " . $_SESSION['compCity'] . "<br>" . "Вам на '" . mb_strtoupper(
                                        lastLetter($_SESSION['compCity'])
                                    ) . "'";
                            } else {
                                $dialogueMess .= "Ваш город - " . $_SESSION['playerCity'] . ". Мне на '" . mb_strtoupper(
                                        lastLetter($_SESSION['playerCity'])
                                    ) . "'" . ".<br> Мой ответ : " . $_SESSION['compCity'] . "<br>" . "But game over. Computer win. Last city - " . $_SESSION['compCity'];
                            }
                        }
                    };
                };
            } else {
                $_SESSION["message"] = "Я не знаю такой город - " . $_SESSION["postCity"] . ". Попробуйте другой<br>";
                $dialogueMess .= ($_SESSION["message"]);
                unset($_SESSION["message"]);
            };
            //-------------------------------

        } else {
            $dialogueMess .= "<br>Неверная буква. Попробуйте снова. Вам нужна " . lastLetter(
                    $_SESSION['compCity']
                );
        }
    } else {
        $_SESSION["message"] = "Введите название города";
        $dialogueMess = $_SESSION["message"];
        unset($_SESSION["message"]);
    };
};


$query = $pdo->prepare("SELECT * FROM usedCities");
$query->execute();
$logs = $query->fetchAll();

if (!empty($logs)) {
    $logs = array_reverse($logs);
    foreach ($logs as $log) {
        $divData ['name'] = $log['name'];
    };
};

header('Content-Type: application/json');
echo json_encode(array("dialogue" => $dialogueData, "div" => $divData));
?>