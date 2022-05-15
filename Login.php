<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Start een session
session_start();

// Als de user al is ingelogd redirect hem naar de homepagina
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: Index.php");
    exit;
}
//Database connectie
require 'db-con.php';

// Variablen vantevoren declareren met lege waardes om te gebruiken in het process
$studentennummer = $password = "";
$studentennummer_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Check of de gebruikersnaam leeg is
    if (empty(trim($_POST["studentennummer"]))) {
        $studentennummer_err = "Voer een studentennummer in";
    } else {
        $studentennummer = trim($_POST["studentennummer"]);
    }

    //check of het wachtwoord leeg is
    if (empty(trim($_POST["password"]))) {
        $password_err = "Voer een wachtwoord in";
    } else {
        $password = trim($_POST["password"]);
    }

    //Valideer het wachtwoord
    if (empty($studentennummer_err) && empty($password_err)) {
        $sql = "SELECT id, studentennummer, password FROM users WHERE studentennummer = :studentennummer";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":studentennummer", $param_studentennummer, PDO::PARAM_STR);

            //zet de parameters
            $param_studentennummer = trim($_POST["studentennummer"]);

            //Probeer dit tot werking te stellen
            if ($stmt->execute()) {
                //checken of naam bestaat
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row["id"];
                        $studentennummer = $row["studentennummer"];
                        $hashed_password = $row["password"];
                        if (password_verify($password, $hashed_password)) {
                            //Als het wachtwoord is geverifieerd dan starten we een session
                            session_start();
                            // We slaan alle data op in de session
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["studentennummer"] = $studentennummer;
                            //Gebruiker naar index sturen
                            header("location: Index.php");

                        }else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid studentennummer or password.";
                        }
                    }
                }else{
                    // Password is not valid, display a generic error message
                    $login_err = "Invalid studentennummer or password.";
                }

            }else{
                // -
               echo "Iets ging verkeerd, probeer opnieuw.";
            }
            unset($stmt);
        }
    }
    //close con
    unset($pdo);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home - login</title>
</head>
<body>

<?php if(!empty($login_err)){
    echo $login_err;
} ?>
<form action="<?php $_SERVER["PHP_SELF"]?>" method="post">
    studentennummer: <input type="text" name="studentennummer" <?php echo(!empty($studentennummer_err)) ? 'invalid' : ''; ?> value="<?php echo $studentennummer; ?>"><br>
    Wachtwoord: <input type="password" name="password" <?php echo(!empty($password_err)) ? 'invalid' : ''; ?>"><br>
    <span class="invalid-feedback"><?php echo $studentennummer_err; ?></span>
    <span class="invalid-feedback"><?php echo $password_err; ?></span>
    <input type="Submit" value="Login">
</form>

Nog geen account? <a href="register.php">Maak account aan</a>


</body>
</html>