<?php include "config.php";?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.flaty.css">
    <title>Accueil</title>
    <style>
        .carousel img {
            height: 500px;
            margin: 0 auto;
            object-fit: contain;
        }

        .carousel-control-prev-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23000' viewBox='0 0 8 8'%3E%3Cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3E%3C/svg%3E") !important;
        }

        .carousel-control-next-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23000' viewBox='0 0 8 8'%3E%3Cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3E%3C/svg%3E") !important;
        }
    </style>
    
</head>

<body>

    <!----------NAVBAR---------->

    <?php include "navbar.php" ?>

    <!----------PRESENTATION---------->

    <?php
    $sql = "SELECT * FROM tableaux ORDER BY `ID` DESC LIMIT 3";
    if ($result = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            if ($row = mysqli_fetch_array($result)) {
                $img1 = $row['Image'];
                $tableau1 = $row['NomTableau'];
                $peintre1 = $row['Peintre'];
            }
            if ($row = mysqli_fetch_array($result)) {
                $img2 = $row['Image'];
                $tableau2 = $row['NomTableau'];
                $peintre2 = $row['Peintre'];
            }
            if ($row = mysqli_fetch_array($result)) {
                $img3 = $row['Image'];
                $tableau3 = $row['NomTableau'];
                $peintre3 = $row['Peintre'];
            }
        }
    }
    ?>
    <div class="container">
        <!----CAROUSEL---->
        <div class="d-flex flex-column align-items-center justify-content-center">
            <h1 class="fw-bold my-4">Tableaux les plus récents</h1>
        </div>

        <div id="carousel" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner rounded shadow-lg">
                <div class="carousel-item active py-5">
                    <img src="<?php echo $img1; ?>" class="d-block w-100 mb-3" alt="../<?php echo $img1; ?>">
                    <h1 class="text-center"><?php echo $tableau1; ?></h1>
                    <p class="text-center"><strong>- <?php echo $peintre1; ?></strong></p>
                </div>
                <div class="carousel-item py-5">
                    <img src="<?php echo $img2; ?>" class="d-block w-100 mb-3" alt="../<?php echo $img2; ?>">
                    <h1 class="text-center"><?php echo $tableau2; ?></h1>
                    <p class="text-center"><strong>- <?php echo $peintre2; ?></strong></p>
                </div>
                <div class="carousel-item py-5">
                    <img src="<?php echo $img3; ?>" class="d-block w-100 mb-3" alt="../<?php echo $img3; ?>">
                    <h1 class="text-center"><?php echo $tableau3; ?></h1>
                    <p class="text-center"><strong>- <?php echo $peintre3; ?></strong></p>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!----------FOOTER---------->

    <?php include "footer.php" ?>

    <!----------SCRIPTS---------->

    <script src="js/theme.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>