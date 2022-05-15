<?php
//PDO - config
$host = 'localhost';
$db = '85133';
$user = 'viktor';
$password = 'admin';

//Met PDO gebruik je een DSN ( Data Source Name )

$dsn = "mysql:host=localhost;dbname=85133;charset=UTF8";

try {
    // PDO object aanmaken daarbij heb je nodig het DSN , Username & password.
    // Dit is dus gewoon een instance van de PDO class.

    $pdo = new PDO($dsn, $user, $password);
    if($pdo) {
        echo "&#128994;" . $db;
    }

} catch(PDOException $e){
    echo $e->getMessage();
}
