<?php
// Database connexion
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'bddgalerie');

// Connexion à la base de données
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verifier la connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Démarrer la session
session_start();

//Verification si admin
if(isset($_SESSION['login'])){
    $sql = "SELECT `admin` FROM users WHERE `login`= ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind les variables à la requête préparée 
        mysqli_stmt_bind_param($stmt, "s", $param_login);  
        // Set parameters
        $param_login = $_SESSION['login'];
        // executer la requête
        if (mysqli_stmt_execute($stmt)) {
            // opération effectuée, retour
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($row['admin'] == 1) {
                $_SESSION['admin'] = 1;
            }
        } else {
            echo "Oops! une erreur est survenue.";
        }
    }
    // Close statement
    mysqli_stmt_close($stmt);
}

// Only show errors
error_reporting(E_ERROR);