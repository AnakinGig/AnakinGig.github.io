<?php
include "config.php";

if (isset($_SESSION['login'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Valider le commentaire
        $input_commentaire = trim($_POST["Commentaire"]);
        if (empty($input_commentaire)) {
            $commentaire_err = "Veillez entrez un message.";
        } else {
            $commentaire = $input_commentaire;
        }

        if (empty($commentaire_err)) {
            $sql = "INSERT INTO `commentaires` (`User`,`Message`) VALUES (?, ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind les variables à la requête préparée 
                mysqli_stmt_bind_param($stmt, "ss", $param_login, $param_commentaire);
                // Set paramètres
                $param_login = $_SESSION['login'];
                $param_commentaire = $input_commentaire;
                // Executer la requête
                if (mysqli_stmt_execute($stmt)) {
                    echo '<div class="bg-dark position-relative">';
                    echo '<div class="toast-container position-absolute p-3 top-0 start-50 translate-middle-x">';
                    echo '<div class="toast show"><div class="toast-header">';
                    echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">';
                    echo '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" /></svg>';
                    echo '<strong class="mx-2">Succès !</strong></div>';
                    echo '<div class="toast-body" id="toast">';
                    echo 'Le commentaire a bien été poster !';
                    echo '</div></div></div></div>';
                    header('Refresh: 4; url=commentaires.php');
                } else {
                    echo "Oops! une erreur est survenue.";
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
} else {
    $commentaire_err = "Veuillez d'abord vous connecter pour pouvoir envoyer un commentaire.";
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.flaty.css">
    <title>Commentaire</title>
    <script>
        const isSystemThemeSetToDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

        if (isSystemThemeSetToDark) {
            document.documentElement.dataset.bsTheme = "dark";
        };
    </script>
</head>

<body>

    <!----------NAVBAR---------->

    <?php include "navbar.php" ?>


    <div class="container my-4">

        <!----------COMMENT-FORM---------->

        <form class="d-flex flex-column" action="commentaires.php" method="post">
            <div class="form-group">
                <label class="form-label" for="Commentaire">
                    <h2>Envoyer un commentaire : </h2>
                </label>
                <textarea class="form-control required <?php echo (!empty($commentaire_err)) ? 'is-invalid' : ''; ?>" name="Commentaire" id="Commentaire" rows="6" style="resize:none;"></textarea>
                <span class="invalid-feedback"><?php echo $commentaire_err; ?></span>
            </div>
            <input class="btn btn-primary align-self-end mt-4" type="submit" value="Poster">
        </form>

        <!----------COMMENTS---------->


    </div>

    <!----------FOOTER---------->

    <?php include "footer.php" ?>

    <!----------SCRIPTS---------->

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/theme.js"></script>
</body>

</html>