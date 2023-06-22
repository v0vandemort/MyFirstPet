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


//Exploding Inputted File

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

//Sending data to PGSQL table

$query = $pdo->prepare(
    "CREATE TABLE IF NOT EXISTS cities(
           id SERIAL PRIMARY KEY,
           name TEXT NOT NULL,
           lat REAL,
           lng REAL,
           UNIQUE (name, lat, lng))"
);

$query->execute();

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
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------



?>


</body>
</html>
