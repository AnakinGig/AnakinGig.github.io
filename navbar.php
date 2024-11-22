<nav class="navbar navbar-expand-md bg-primary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand mx-md-4" href="index.php"><strong>Galerie d'art</strong></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''); ?>" href="index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) === 'tableaux.php' ? 'active' : ''); ?>" href="tableaux.php">Tableaux</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) === 'commentaires.php' ? 'active' : ''); ?>" href="commentaires.php">Commentaires</a>
                </li>
            </ul>
            <ul class="navbar-nav me-md-4">
                <li class="nav-item d-flex align-items-center me-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-sun-fill me-2" viewBox="0 0 16 16">
                        <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8M8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0m0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13m8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5M3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8m10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0m-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0m9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707M4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708" />
                    </svg>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="themeSwitch" onclick="toggleTheme()">
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-moon-fill" viewBox="0 0 16 16">
                        <path d="M6 .278a.77.77 0 0 1 .08.858 7.2 7.2 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277q.792-.001 1.533-.16a.79.79 0 0 1 .81.316.73.73 0 0 1-.031.893A8.35 8.35 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.75.75 0 0 1 6 .278" />
                    </svg>
                </li>
                <?php
                if (isset($_SESSION['login'])) {
                    echo '<li class="nav-item">';
                    echo '<button type="button" class="btn btn-secondary w-100 shadow">';
                    echo '<a class="nav-link" href="logout.php">Se déconnecter</a></button></li>';
                } else {
                    echo '<li class="nav-item">';
                    echo '<button type="button" class="btn btn-secondary w-100 shadow">';
                    echo '<a class="nav-link" href="login.php">Se connecter</a></button></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>