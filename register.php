<?php
require "config.php";

if (isset($_SESSION['login'])) {
    header("location:index.php");
    exit;
}

// Définir les variables
$login = $password = $email = $bday = "";
$login_err = $password_err = $email_err = $bday_err = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valider le login
    $input_login = trim($_POST["login"]);
    if (empty($input_login)) {
        $login_err = "Veillez entrez un login.";
    } elseif (!filter_var($input_login, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $login_err = "Veillez entrez un login valid.";
    } else {
        $sql = "SELECT `login` FROM users WHERE `login`= ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind les variables à la requête préparée 
            mysqli_stmt_bind_param($stmt, "s", $param_login);
            // Set paramètres
            $param_login = $input_login;
            // Executer la requête
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_fetch($stmt);
                if ($result == $input_login) {
                    $login_err = "Cet utilisateur existe déjà.";
                } else {
                    $login = $input_login;
                }
            } else {
                echo "Oops! une erreur est survenue.";
            }
        }
        mysqli_stmt_close($stmt);
    }

    // Valide password 
    $input_password = trim($_POST["password"]);
    if (empty($input_password)) {
        $password_err = "Veillez entrez un mot de passe.";
    } elseif (!preg_match('/^(?=.*\d)(?=.*[@#\-_.$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_.$%^&+=§!\?]{8,24}$/', $input_password)) {
        $password_err = "Veuillez entrez un mot de passe entre 8 et 24 caractères avec au moins une lettre majuscule, une lettre minuscule, un chiffre et un symbole.";
    } else {
        $password = sha1($input_password);
    }

    // Vérifiez les erreurs avant enregistrement
    if (empty($login_err) && empty($password_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (login, password) VALUES (?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {

            // Bind les variables à la requête préparée 
            mysqli_stmt_bind_param($stmt, "ss", $param_login, $param_password);

            // Set les paramètres
            $param_login = $login;
            $param_password = $password;

            // Executer la requête
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['login'] = $login;
                header("location:index.php");
                exit();
            } else {
                echo "Oops! une erreur est survenue.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.flaty.css">
    <title>Créer un compte</title>
</head>

<body>
    <a href="index.php" class="btn btn-secondary mx-2 my-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-return-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5" />
        </svg> Retour
    </a>
    <div class="py-3 py-md-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <div class="card border border-light-subtle rounded-3 shadow-sm">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <div class="text-center mb-3">
                                <a href="index.php">
                                    <img src="images/Logo.jpg" alt="Art Galerie Logo" width="236" height="180">
                                </a>
                            </div>
                            <h2 class="fs-6 fw-normal text-center text-secondary mb-4">Créer votre compte</h2>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="row gy-2 overflow-hidden">
                                    <div class="form-group col-12 mb-3 required">
                                        <div class="form-floating <?php echo (!empty($login_err)) ? 'is-invalid' : ''; ?>">
                                            <input type="text" class="form-control <?php echo (!empty($login_err)) ? 'is-invalid' : ''; ?>" name="login" id="login" placeholder="login">
                                            <label for="login" class="form-label">Nom d'utilisateur</label>
                                            <span class="invalid-feedback"><?php echo $login_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 mb-3 required">
                                        <div class="form-floating <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                                            <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" name="password" id="password"placeholder="password">
                                            <label for="password" class="form-label">Mot de passe</label>
                                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid my-3">
                                            <button class="btn btn-primary btn-lg" type="submit">Créer votre compte</button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <p class="m-0 text-secondary text-center">Déjà un compte ? <a href="login.php">Se connecter</a></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>