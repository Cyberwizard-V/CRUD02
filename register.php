<?php
    // Includen van de database connectie ( Config )
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'db-con.php';
require 'functions.php';

    //patterns
$emailPattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

    // Lege variablen voor het invoeren in de database
$username = $password = $confirm_password = $achternaam = $studentennummer = $postcode = $adres = $woonplaats =  $email ="";

    // Lege variablen voor error handling in het registreer formulier
$username_err = $password_err = $confirm_password_err =
$achternaam_err = $studentennummer_err = $postcode_err =
$adres_err = $woonplaats_err = $email_err = "";



if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check of username
    if(empty(trim($_POST["username"]))){
        //Error handling , als username leeg is dan : ERROR
        $username_err = "Voer je naam in";

    }  elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){

        $username_err = "Gebruikersnaam kan alleen letters, nummers en underscores hebben";
    }else {
        // Prepared statement
        $sql = "SELECT id FROM users WHERE username = :username";
        // Bind de variables aan de prepared statemen als parameters
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
        }
        //Zet de parameters
        $param_username = trim($_POST["username"]);

        //Check of de gebruikersnaam al bestaat
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                $username_err = "De ingevoerde gebruikersnaam is al in gebruik";

            } else {
                $username = trim($_POST["username"]);
            }
        } else {
            echo "Er ging iets mis, probeer later opnieuw";
        }
        unset($stmt);
    }

    // Verifieer studentennummer
    if(empty(trim($_POST["studentennummer"]))){
        //Error handling , als student nummmer niet is ingevoerd
        $studentennummer_err = "Voer je studentennummer in";

    }  elseif(strlen(trim($_POST['studentennummer'])) < 5 ){

        $studentennummer_err = "Studentennummer is minder dan 5.";
    }else {
        // Prepared statement
        $sql = "SELECT id FROM users WHERE studentennummer = :studentennummer";
        // Bind de variables aan de prepared statemen als parameters
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":studentennummer", $param_studentennummer, PDO::PARAM_STR);
        }
        //Zet de parameters
        $param_studentennummer = trim($_POST["studentennummer"]);

        //Check of de gebruikersnaam al bestaat
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                $studentennummer_err = "De ingevoerde studentennummer is al in gebruik, neem contact op met de 
                server beheerders. Email : 85133@glr.nl";

            } else {
                $studentennummer = trim($_POST["studentennummer"]);
            }
        } else {
            echo "Er ging iets mis, probeer later opnieuw";
        }
        unset($stmt);
    }
    // Verifieer wachtwoord
    if(empty(trim($_POST["password"]))){
        $password_err = "Voer een wachtwoord in";
    }elseif(strlen(trim($_POST["password"])) < 6 ){
        $password_err = "Je wachtwoord moet minstens 6 tekens zijn";
    }else{
        $password = trim($_POST["password"]);
    }
    // Achternaam check
    if(empty(trim($_POST["achternaam"]))){
        $achternaam_err = "Voer een achternaam in";
    }else{
        $achternaam = trim($_POST["achternaam"]);
    }
    // Postcode check
    if(empty(trim($_POST["postcode"]))){
        $postcode_err = "Voer een postcode in";
    }elseif(strlen(trim($_POST["postcode"])) < 6 || strlen(trim($_POST["postcode"])) > 6 ){
        $postcode_err = "Ingevoerde postcode is onjuist. Teveel tekens";
    }else{
        $postcode = trim(strtoupper($_POST["postcode"]));
    }
    // Adres check
    if(empty(trim($_POST["adres"]))){
        $adres_err = "Voer een adres in";
    }else{
        $adres = trim(strtoupper($_POST["adres"]));
    }
    // Email check
    if(empty(trim($_POST["email"]))){
        $email_err = "Voer een E-mail in";
    }else{
        $email = trim(($_POST["email"]));
    }

    // Woonplaats check
    if(empty(trim($_POST["woonplaats"]))){
        $woonplaats_err = "Voer een woonplaats in";
    }else{
        $woonplaats = trim($_POST["woonplaats"]);
    }

    // Verifieer wachtwoord ( Verifieer wachtwoord )
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Voer een wachtwoord in";
    }else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Wachwoord is niet hetzelfde";
        }
    }

    //Check input errors voordat je het in de database zet
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)
        && empty($achternaam_err) && empty($studentennummer_err) && empty($postcode_err) && empty($adres_err) && empty($email_err)){
        //Prepared statement voor het inserten van het wachtwoord // username
        $sql = "INSERT INTO users (username, password, achternaam, studentennummer, postcode, adres, woonplaats, email) VALUES (:username, :password, :achternaam, :studentennummer, :postcode, :adres, :woonplaats, :email)";

        if($stmt = $pdo->prepare($sql)){

            //zet de parameters
            $param_username = $username;
            $param_achternaam = $achternaam;
            $param_studentennummer = $studentennummer;
            $param_postcode = $postcode;
            $param_adres = $adres;
            $param_woonplaats = $woonplaats;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash;

            //Probeer de statement tot werking te stellen
            if($stmt->execute(array(
                ":username" => $param_username,
                ":password" => $param_password,
                ":achternaam" => $param_achternaam,
                ":studentennummer" => $param_studentennummer,
                ":postcode" => $param_postcode,
                ":adres" => $param_adres,
                ":woonplaats" => $param_woonplaats,
                ":email" => $param_email,
            ))){
                header("location:Login.php");
            }else{
                echo "Iets ging mis, probeer later opnieuw";
            }
            // Close statement
            unset($stmt);
        }
        //Close de connectie
        unset($pdo);
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <title>Registreer - Maak een account aan </title>
</head>
<body>
<div class="row1 form">
    <div class="full-row">
        <div class="blocks-container">
            <div class="registreer formulier">
            <h3>Registreer</h3>

                <form method="post" action="<?php $_SERVER["PHP_SELF"]?>" >
                <div class="form element">
                <label>Naam</label>
                <div class="label-form col">
                    <input type="text" class="form-control" name="username" <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>
                        value="<?php echo $username; ?>">
                    <div class="invalid-feedback"><?php echo $username_err; ?></div>
                </div>
                </div>
               
                <div class="form element">
                    <label>Achternaam</label>
                    <div class="label-form col">

                            <input class="form-control" type="text" name="achternaam"
                                <?php echo (!empty($achternaam_err)) ? 'is-invalid' : ''; ?>value="<?php echo $achternaam; ?>" ">
                            <div class="invalid-feedback"><?php echo $achternaam_err; ?></div>
                    </div>
                </div>

                <div class="form element">
                                    <label>Studentennummer</label>
                    <div class="label-form col">
                        <input class="form-control" type="text" name="studentennummer" <?php echo (!empty($studentennummer_err)) ? 'is-invalid' : ''; ?>
                            value="<?php echo $studentennummer; ?>" " >
                        <div class="invalid-feedback"><?php echo $studentennummer_err; ?></div>
                    </div>
                </div>    

                <div class="form element">
                    <label>Postcode</label>
                    <div class="label-form col">
                        <input type="text" class="form-control" name="postcode" <?php echo (!empty($postcode_err)) ? 'is-invalid' : ''; ?>
                            value="<?php echo $postcode; ?>" " >
                        <div class="invalid-feedback"><?php echo $postcode_err; ?></div>
                    </div>
                </div>
                <div class="form element">
                    <label>Adres</label>
                    <div class="label-form col">
                            <input type="text" class="form-control" name="adres" <?php echo (!empty($adres_err)) ? 'is-invalid' : ''; ?>
                                value="<?php echo $adres; ?>" " >
                            <div class="invalid-feedback"><?php echo $adres_err; ?></div>
                    </div>
                </div>
                <div class="form element">
                <label>Woonplaats</label>
                    <div class="label-form col">
                            <input type="text" class="form-control" name="woonplaats" <?php echo (!empty($woonplaats_err)) ? 'is-invalid' : ''; ?>
                                value="<?php echo $woonplaats; ?>" " >
                            <div class="invalid-feedback"><?php echo $woonplaats_err; ?></div>
                    </div>
                </div>
                <div class="form element">
                <label>E-mail</label>
                    <div class="label-form col">
                        <input type="text" class="form-control" name="email" <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>
                            value="<?php echo $email; ?>" " >
                        <div class="invalid-feedback"><?php echo $email_err; ?></div>
                    
                </div>
                </div>
                <div class="form element">
                    <label>Wachtwoord</label>
                    <div class="label-form col">
                        <input type="password" class="form-control" name="password" <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>
                            value="<?php echo $password; ?>">
                        <div class="invalid-feedback"><?php echo $password_err; ?></div>
                    </div>
                </div>
                <div class="form element">
                    <label>Verifieer Wachtwoord</label>
                    <div class="label-form col">
                        <input type="password" class="form-control"
                            name="confirm_password" <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>
                            value="<?php echo $confirm_password; ?>">
                        <div class="invalid-feedback"><?php echo $confirm_password_err; ?></div>
                    </div>
                </div>
                            <input class="btn btn-dark" type="Submit" value="Registreer">
                    </form>
                        <div class="tekst content">
                            <p>Heb je al een account? <a href="Login.php">Log in</a></p>
                        </div>
                </div>
        </div>
    </div>
</div>

</body>
</html>
