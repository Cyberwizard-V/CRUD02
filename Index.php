<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require 'db-con.php';

if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true){
    header("location: Login.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home - <?php echo $_SESSION["studentennummer"]; ?> </title>
</head>
<body>

<h3>Welkom op de home pagina <?php echo $_SESSION["studentennummer"]; ?></h3>

<a href="Vragen.php">Vragenlijst</a>
<a href="Gebruiker.php">Gebruiker</a>


</body>
</html>