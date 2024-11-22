<?php require "config.php";?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.flaty.css">
    <style>
        .text-justify {
            text-align: justify;
        }

        .card-title {
            max-width: 95%;
        }
    </style>
    <script src="assets/theme.js"></script>
    <title>Tableaux</title>
</head>

<body>

    <!----------NAVBAR---------->

    <?php include "navbar.php" ?>

    <!---------FILTERS + ADD--------->
    <div class="d-flex mt-4">
        <?php
        if (isset($_SESSION['admin'])) {
            echo '<a href="add_tableau.php" type="button" class="btn btn-lg btn-success ms-3 align-items-center d-flex"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="white" class="bi bi-plus-lg" viewBox="0 0 16 16">';
            echo '<path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>';
            echo '</svg>Nouveau tableau</a>';
        }
        ?>
    </div>
    <div class="container-flex d-flex justify-content-center justify-content-md-end align-items-center mt-4">
        <form action="tableaux.php" method="post" class="d-flex flex-md-row flex-column-reverse me-4">
            <div class="d-flex flex-md-row mt-4 mt-md-0">
                <select class="form-select me-4 required" name="filter" id="filter">
                    <option selected disabled>Filtre de recherche</option>
                    <option <?php if (isset($_POST['filter']) && $_POST['filter'] == "NomTableau") {
                                echo 'selected ';
                            } ?> value="NomTableau">Tableau</option>
                    <option <?php if (isset($_POST['filter']) && $_POST['filter'] == "Peintre") {
                                echo 'selected ';
                            } ?>value="Peintre">Artiste</option>
                    <option <?php if (isset($_POST['filter']) && $_POST['filter'] == "Style") {
                                echo 'selected ';
                            } ?>value="Style">Style</option>
                    <option <?php if (isset($_POST['filter']) && $_POST['filter'] == "DateCreation") {
                                echo 'selected ';
                            } ?>value="DateCreation">Date de création</option>
                </select>
            </div>
            <div class="d-flex flex-md-row">
                <input class="form-control me-2" type="search" name="search" placeholder="Search" value="<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {echo $_POST['search'];} ?>">
                <button class="btn btn-secondary" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!---------MODAL-DELETE------>
    <?php
    if (isset($_SESSION['admin'])) {
        echo '<div class="modal" id="modal">';
        echo '<div class="modal-dialog" role="document">';
        echo '<div class="modal-content">';
        echo '<div class="modal-header">';
        echo '<h5 class="modal-title">Suppression</h5>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>';
        echo '<div class="modal-body">';
        echo 'Êtes vous sûr de vouloir supprimer ce tableau ?<br> Cette action est irréversible.</div>';
        echo '<div class="modal-footer">';
        echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post" class="d-flex align-items-center justify-content-center w-100">';
        echo '<input type="hidden" name="id" id="id"/>';
        echo '<input type="submit" value="Supprimer" class="btn btn-lg btn-danger me-3">';
        echo '<button type="button" class="btn btn-lg btn-secondary" data-bs-dismiss="modal">Fermer</button>';
        echo '</form></div></div></div></div>';

        // Set parameters
        if (isset($_POST["id"]) && !empty($_POST["id"])) {

            $sql = "SELECT * FROM tableaux WHERE `ID`= ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind les variables à la requête préparée 
                mysqli_stmt_bind_param($stmt, "s", $param_id);

                $param_id = $_POST["id"];
                // executer la requête
                if (mysqli_stmt_execute($stmt)) {
                    // opération effectuée, retour
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) == 1) {
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        unlink($row['Image']);
                    }
                } else {
                    echo "Oops! une erreur est survenue.";
                }
            }
            mysqli_stmt_close($stmt);

            $sql = "DELETE FROM tableaux WHERE id = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $param_id);

                $param_id = trim($_POST["id"]);

                if (!mysqli_stmt_execute($stmt)) {
                    echo "Oops! une erreur est survenue.";
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
    ?>

    <script>
        function modalId(ID) {
            id = document.getElementById("id");
            id.value = ID;
        }
    </script>

    <!---------TABLEAUX---------->
    <div class="container">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search']) && isset($_POST['filter'])) {
            $search = $_POST['search'];
            $filter = $_POST['filter'];
            $sql = "SELECT * FROM tableaux WHERE $filter like '%$search%'";
        } else {
            $sql = "SELECT * FROM tableaux";
        }

        if ($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) > 0) {
                echo '<div class="row m-5" data-masonry=\'{"percentPosition": true }\'>';
                while ($row = mysqli_fetch_array($result)) {
                    echo '<div class="col-lg-4 col-md-12">';
                    echo '<div class="card mb-4 shadow-sm">';
                    echo '<img src="' . $row['Image'] . '" alt="' . $row['Image'] . '" class="card-img-top">';
                    echo '<div class="card-body">';
                    if (isset($_SESSION['admin'])) {
                        echo '<a class=" position-absolute end-0 me-3" data-bs-toggle="modal" data-bs-target="#modal" onclick="modalId(' . $row['ID'] . ')">';
                        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="red" class="bi bi-trash3-fill" viewBox="0 0 16 16">';
                        echo '<path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />';
                        echo '</svg></a>';
                    }
                    echo '<h4 class="card-title">' . $row["NomTableau"] . ' (' . $row["DateCreation"] . ')<br><strong> ' . number_format($row["Prix"], 0, ',', ' ') . '€</strong></h4>';
                    echo '<p class="card-text">Style ' . $row["Style"] . '</p>';
                    echo '<p class="card-text text-secondary text-justify">' . $row['Commentaire'] . '</p>';
                    echo '<p class="card-text"><strong>- ' . $row['Peintre'] . '</strong></p></div></div></div>';
                }
                echo "</div>";
                mysqli_free_result($result);
            } else {
                echo "<h1 class='d-flex align-items-center justify-content-center mt-5'>Pas de tableaux !</h1>";
            }
        } else {
            echo "Error : " . mysqli_error($link);
        }

        // Fermer la connection
        mysqli_close($link);
        ?></div>

    <!----------FOOTER---------->

    <?php include "footer.php" ?>

    <!----------SCRIPTS---------->

    <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/theme.js"></script>
</body>

</html>