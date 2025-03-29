<?php
// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Supprimer toutes les variables de session
session_unset();

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion (en s'assurant que l'URL est correcte)
header("Location: ../../client/auth/login.php");
exit();
