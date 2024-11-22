<?php
require "config.php";

$nomTableau = $peintre = $style = $dateCreation = $prix = $commentaire = $image = "";
$nomTableau_err = $peintre_err = $style_err = $dateCreation_err = $prix_err = $commentaire_err = $image_err = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valider nom tableau 
    $input_nomTableau = trim($_POST["NomTableau"]);
    if (empty($input_nomTableau)) {
        $nomTableau_err = "Veillez entrez un nom pour le tableau.";
    } else {
        $nomTableau = $input_nomTableau;
    }

    // Valider nom peintre
    $input_peintre = trim($_POST["Peintre"]);
    if (empty($input_peintre)) {
        $peintre_err = "Veillez entrez un nom pour le peintre.";
    } else {
        $peintre = $input_peintre;
    }

    // Valider style 
    $input_style = trim($_POST["Style"]);
    if (empty($input_style)) {
        $style_err = "Veillez entrez un style.";
    } else {
        $style = $input_style;
    }

    // Valider date de création
    $input_dateCreation = trim($_POST["DateCreation"]);
    if (empty($input_dateCreation)) {
        $dateCreation_err = "Veillez entrez l'année de création du tableau.";
    } else if (intval($input_dateCreation) > date('Y')) {
        $dateCreation_err = "Veillez entrez une année valide.";
    } else {
        $dateCreation = $input_dateCreation;
    }

    // Valider prix
    $input_prix = trim($_POST["Prix"]);
    if (empty($input_prix)) {
        $prix_err = "Veillez entrez le prix du tableau.";
    } else if (intval($input_prix) < 0) {
        $prix_err = "Veillez entrez un prix valide.";
    } else {
        $prix = $input_prix;
    }

    // Valider commentaire
    $input_commentaire = trim($_POST["Commentaire"]);
    if (empty($input_commentaire)) {
        $commentaire_err = "Veillez entrez un commentaire.";
    } else {
        $commentaire = $input_commentaire;
    }

    // Valider image
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["Image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (empty($_FILES["Image"]["name"])) {
        $image_err = "Veillez entrez une image.";
    } else if (getimagesize($_FILES["Image"]["tmp_name"]) == false) {
        $image_err = "Le fichier n'est pas une image.";
    } else if (file_exists($target_file)) {
        $image_err = "Un tableau porte déjà ce nom. Veuillez renommer l'image.";
    } else if ($_FILES["Image"]["size"] > 50000000) { //Limit 50Mo
        $image_err = "L'image est trop volumineuse. (Limite de 50Mo)";
    } else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $image_err = "Seul les images en .JPG, .JPEG, .PNG et .GIT sont autorisés.";
    } else {
        $image = $target_file;
    }

    // Vérifiez les erreurs avant enregistrement
    if (empty($nomTableau_err) && empty($peintre_err) && empty($style_err) && empty($dateCreation_err) && empty($prix_err) && empty($commentaire_err) && empty($image_err)) {
        // Prepare insert statement
        $sql = "INSERT INTO tableaux (NomTableau, Peintre, Style, DateCreation, Prix, Commentaire, Image) VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {

            // Bind les variables à la requête préparée 
            mysqli_stmt_bind_param($stmt, "sssiiss", $param_nomTableau, $param_peintre, $param_style, $param_dateCreation, $param_prix, $param_commentaire, $param_image);

            // Set les paramètres
            $param_nomTableau = $nomTableau;
            $param_peintre = $peintre;
            $param_style = $style;
            $param_dateCreation = $dateCreation;
            $param_prix = $prix;
            $param_commentaire = $commentaire;
            $param_image = $image;

            // Executer la requête
            if (mysqli_stmt_execute($stmt)) {
                // Opération effectuée, retour
                if (move_uploaded_file($_FILES["Image"]["tmp_name"], $target_file)) {
                    echo '<div class="bg-dark position-relative">';
                    echo '<div class="toast-container position-absolute p-3 top-0 start-50 translate-middle-x">';
                    echo '<div class="toast show"><div class="toast-header">';
                    echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">';
                    echo '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" /></svg>';
                    echo '<strong class="mx-2">Succès !</strong></div>';
                    echo '<div class="toast-body" id="toast">';
                    echo 'Le tableau ' . htmlspecialchars(basename($_FILES["Image"]["name"])) . ' à été uploader.<br> Redirection en cours...</div></div></div></div>';
                    header("refresh:3;url=tableaux.php");
                } else {
                    echo '<div class="bg-dark position-relative">';
                    echo '<div class="toast-container position-absolute p-3 top-0 start-50 translate-middle-x">';
                    echo '<div class="toast show"><div class="toast-header">';
                    echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-bug-fill" viewBox="0 0 16 16">';
                    echo '<path d="M4.978.855a.5.5 0 1 0-.956.29l.41 1.352A5 5 0 0 0 3 6h10a5 5 0 0 0-1.432-3.503l.41-1.352a.5.5 0 1 0-.956-.29l-.291.956A5 5 0 0 0 8 1a5 5 0 0 0-2.731.811l-.29-.956z"/>';
                    echo '<path d="M13 6v1H8.5v8.975A5 5 0 0 0 13 11h.5a.5.5 0 0 1 .5.5v.5a.5.5 0 1 0 1 0v-.5a1.5 1.5 0 0 0-1.5-1.5H13V9h1.5a.5.5 0 0 0 0-1H13V7h.5A1.5 1.5 0 0 0 15 5.5V5a.5.5 0 0 0-1 0v.5a.5.5 0 0 1-.5.5zm-5.5 9.975V7H3V6h-.5a.5.5 0 0 1-.5-.5V5a.5.5 0 0 0-1 0v.5A1.5 1.5 0 0 0 2.5 7H3v1H1.5a.5.5 0 0 0 0 1H3v1h-.5A1.5 1.5 0 0 0 1 11.5v.5a.5.5 0 1 0 1 0v-.5a.5.5 0 0 1 .5-.5H3a5 5 0 0 0 4.5 4.975"/></svg>';
                    echo '<strong class="mx-2">Erreur !</strong></div>';
                    echo '<div class="toast-body" id="toast">';
                    echo 'L\'image n\'a pas pu être ajouter.<br> Veuillez réessayer plus tard.<br>Redirection... </div></div></div></div>';
                    header("refresh:3;url=tableaux.php");
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
} ?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.flaty.css">
    <title>Ajouter un tableau</title>
</head>

<body>
    <a href="index.php" class="btn btn-secondary mx-2 my-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-return-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5" />
        </svg> Retour
    </a>
    <div class=" pb-3 pb-md-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-11 col-md-9 col-lg-7 col-xl-6">
                    <div class="card border border-light-subtle rounded-3 shadow-sm">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <h2 class="fs-2 fw-normal text-center text-secondary mb-4">Ajoutez un tableau</h2>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                                <div class="row gy-2 overflow-hidden">
                                    <div class="col-6 form-group mb-3">
                                        <div class="form-floating required <?php echo (!empty($nomTableau_err)) ? 'is-invalid' : ''; ?>">
                                            <input type="text" class="form-control <?php echo (!empty($nomTableau_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nomTableau; ?>" name="NomTableau" id="NomTableau" placeholder="NomTableau">
                                            <label for="NomTableau" class="form-label">Nom du tableau</label>
                                            <span class="invalid-feedback"><?php echo $nomTableau_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-6 form-group mb-3">
                                        <div class="form-floating required <?php echo (!empty($peintre_err)) ? 'is-invalid' : ''; ?>">
                                            <input type="text" class="form-control <?php echo (!empty($peintre_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $peintre; ?>" name="Peintre" id="Peintre" placeholder="Peintre">
                                            <label for="Peintre" class="form-label">Peintre</label>
                                            <span class="invalid-feedback"><?php echo $peintre_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-7 form-group mb-3">
                                        <div class="form-floating required <?php echo (!empty($style_err)) ? 'is-invalid' : ''; ?>">
                                            <input type="text" class="form-control <?php echo (!empty($style_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $style; ?>" name="Style" id="Style" placeholder="Style">
                                            <label for="Style" class="form-label">Style du tableau</label>
                                            <span class="invalid-feedback"><?php echo $style_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-5 form-group mb-3">
                                        <div class="form-floating required <?php echo (!empty($dateCreation_err)) ? 'is-invalid' : ''; ?>">
                                            <input type="number" class="form-control <?php echo (!empty($dateCreation_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $dateCreation; ?>" name="DateCreation" id="DateCreation" placeholder="DateCréation">
                                            <label for="DateCréation" class="form-label">Année de création</label>
                                            <span class="invalid-feedback"><?php echo $dateCreation_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Prix</span>
                                            <input type="number" class="form-control required <?php echo (!empty($prix_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $prix; ?>" name="Prix">
                                            <span class="input-group-text">.00€</span>
                                            <span class="invalid-feedback"><?php echo $prix_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="Commentaire" class="form-label">Commentaire :</label>
                                            <textarea class="form-control required <?php echo (!empty($commentaire_err)) ? 'is-invalid' : ''; ?>" name="Commentaire" id="Commentaire" rows="4" style="resize:none;"><?php echo $commentaire; ?></textarea>
                                            <span class="invalid-feedback"><?php echo $commentaire_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="Image" class="form-label">Image du tableau : </label>
                                            <input type="file" class="form-control required <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>" name="Image" id="Image">
                                            <span class="invalid-feedback"><?php echo $image_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid my-3">
                                            <button class="btn btn-primary btn-lg" type="submit">Ajouter</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap.bundle.min.js"></script>
</body>

</html>