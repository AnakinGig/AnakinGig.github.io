<?php
require "config.php";

if (isset($_SESSION['login'])) {
    header("location:index.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate name
    $input_login = trim($_POST["login"]);
    if (empty($input_login)) {
        $login_err = "Veillez entrez un login.";
    } else {
        $sql = "SELECT `login` FROM users WHERE `login`= ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind les variables à la requête préparée 
            mysqli_stmt_bind_param($stmt, "s", $param_login);
            // Set parameters
            $param_login = $input_login;
            // executer la requête
            if (mysqli_stmt_execute($stmt)) {
                // opération effectuée, retour
                $result = mysqli_stmt_fetch($stmt);
                if ($result === null) {
                    $login_err = "Cet utilisateur n'existe pas.";
                } else {
                    $login = $input_login;
                }
            } else {
                echo "Oops! une erreur est survenue.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }

    $input_password = trim($_POST["password"]);
    if (empty($input_password)) {
        $password_err = "Veillez entrez votre mot de passe.";
    } else {
        $sql = "SELECT `password` FROM users WHERE `login`=? AND `password`= ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind les variables à la requête préparée 
            mysqli_stmt_bind_param($stmt, "ss", $param_login, $param_password);
            // Set parameters
            $param_login = $input_login;
            $param_password = sha1($input_password);
            // executer la requête
            if (mysqli_stmt_execute($stmt)) {
                // opération effectuée, retour
                $result = mysqli_stmt_fetch($stmt);
                if ($result === null) {
                    $password_err = "Mot de passe incorrect";
                } else {
                    $_SESSION['login'] = $input_login;
                    header("location:index.php");
                    exit();
                }
            } else {
                echo "Oops! une erreur est survenue.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.flaty.css">
    <title>Se connecter</title>
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
                            <h2 class="fs-6 fw-normal text-center text-secondary mb-4">Connectez vous a votre compte</h2>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="row gy-2 overflow-hidden">
                                    <div class="col-12 form-group mb-3">
                                        <div class="form-floating <?php echo (!empty($login_err)) ? 'is-invalid' : ''; ?>">
                                            <input type="text" class="form-control <?php echo (!empty($login_err)) ? 'is-invalid' : ''; ?>" name="login" id="login" placeholder="login">
                                            <label for="login" class="form-label">Nom d'utilisateur</label>
                                            <span class="invalid-feedback"><?php echo $login_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-12 form-group mb-3">
                                        <div class="form-floating <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                                            <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" name="password" id="password" value="" placeholder="password">
                                            <label for="password" class="form-label">Mot de passe</label>
                                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid my-3">
                                            <button class="btn btn-primary btn-lg" type="submit">Se connecter</button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <p class="m-0 text-secondary text-center">Pas de compte ? <a href="register.php">Créer un compte</a></p>
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