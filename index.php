<?php
require_once 'shared/authMiddleware.php';
require_once 'database.php';

session_start();

if (isset($_SESSION['change_password']) && $_SESSION['change_password']) {
    header("Location: /pages/auth/change_password.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php require_once 'shared/navBar.php'; ?>

    <div class="container mt-5 text-center">
        <h1 class="text-primary">Bienvenue dans la gestion des bus</h1>
        <p class="lead">Utilisez le menu pour naviguer dans l'application.</p>
        <img src="https://source.unsplash.com/800x400/?bus,transport" class="img-fluid rounded mt-3" alt="Bus Image">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
