<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php

try {
    $pdo = new PDO ('pgsql:host=db;port=5432;dbname=cities;user=admin;password=example');
} catch (PDOException $e) {
    echo("Невозможно установить соединение с БД");
}

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

$query = "DROP DATABASE IF EXISTS db";
if ($pdo->exec($query) !== false) {
    echo "Таблица cities удалена<br>";
}

$query = "CREATE TABLE if NOT EXISTS cities(
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    lat REAL,
    lng REAL)";
if ($pdo->exec($query) !== false) {
    echo "Таблица cities создана<br>";
    foreach ($fileData as $row => $data) {
        if ($row === 0) {
            continue;
        }

        $name = $data[$headerKeys["name"]];
        $lat = $data[$headerKeys["lat"]];
        $lng = $data[$headerKeys["lng"]];
//    echo $name . "  " . $lat . "   " . $lng . "   <br><br>";
        $query = "INSERT INTO cities (name, lat, lng)
            VALUES ('" . $name . "', '" . $lat . "', '" . $lng . "')";

        if ($pdo->exec($query) === 0) {
            echo "Запись не добавлена.<br>" . $name . "  " . $lat . "   " . $lng . "   <br><br>";
        }

//    $pdo->exec($query) or
//        $died++;
//        echo " <br>".$name . " " . $lat . " " . $lng." <br>";
//

    }
}
$died = 0;


echo "<br>Строк не добавлено в таблицу: " . $died . "<br>";

?>


</body>
</html>
