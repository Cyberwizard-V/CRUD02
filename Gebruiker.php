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

<?php $currentID = $_SESSION["id"]; ?>
<?php
#Krijg alle data van ingelogde gebruiker
$getUsers = $pdo->prepare("SELECT * FROM `users` WHERE ID=$currentID ");
$getUsers->execute();
$users = $getUsers->fetchAll();
?>
<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $naam = $_POST['naam'];
    $achternaam = $_POST['achternaam'];
    $nummer = $_POST['studentennummer'];
    $Woonplaats = $_POST['Woonplaats'];
    $Adres = $_POST['Adres'];
    $Postcode = $_POST['Postcode'];
    $Email = $_POST['Email'];

    $data = [
        'naam' => $naam,
        'achternaam' => $achternaam,
        'nummer' => $nummer,
        'woonplaats' => $Woonplaats,
        'adres' => $Adres,
        'postcode' => $Postcode,
        'email' => $Email,
    ];

    #PREPARE ZE STATEMENT
    $sql = "UPDATE `users` SET `username`=:naam,
    `Achternaam`=:achternaam,`Studentennummer`=:nummer,
    `Adres`=:adres,`Postcode`=:postcode,`Woonplaats`=:woonplaats,
    `Email`=:email WHERE ID=$currentID";

    $stmt= $pdo->prepare($sql);
    $stmt->execute($data);

    header('location: Index.php');
    
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gebruiker - <?php echo $_SESSION["studentennummer"] . ", ID = " . $currentID; ?> </title>
    <div class="gebruiker">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> " >
            <input type="text" name="naam" value="<?= $users[0]['username'] ?>">
            <input type="text" name="achternaam" value="<?= $users[0]['Achternaam'] ?>">
            <input type="text" name="studentennummer" value="<?= $users[0]['Studentennummer'] ?>">
            <input type="text" name="Woonplaats" value="<?= $users[0]['Woonplaats'] ?>">
            <input type="text" name="Adres" value="<?= $users[0]['Adres'] ?>">
            <input type="text" name="Postcode" value="<?= $users[0]['Postcode'] ?>">
            <input type="text" name="Email" value="<?= $users[0]['Email'] ?>"> 
            <input type="submit" name="update">
        </form>
    </div>

</head>
<body>

