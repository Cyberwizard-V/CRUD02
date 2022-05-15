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
    <title></title>
</head>
<body>

<?php
#PDO QUERY
$stmt = $pdo->query('SELECT * FROM questions');
$counter = 1;
$vraagcounter = 1;
$vraagnaamcounter = 1;
?>
<div class="formulier">
<h2>Vul de onderstaande vragen in</h2>
    <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="post">
<?php
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo "<div class='content vragen" . "'>";
    echo "<h3> Vraag " . $vraagcounter++ . "</h3>";
    echo "<div class='vraag" . $counter++ .  "'>" . $row['Vraag'] . "</div>";
    echo "<textarea name='vraag" . $vraagnaamcounter++ .  "'>" . "</textarea>";
    echo "</div>";
}
?>
</div>
<input type="Submit" value="Verstuur">
</form>

</body>
</html>

