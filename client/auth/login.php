<?php
session_start();
require_once '../../database.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Vérifier si c'est un ADMIN/RESPONSABLE (table users)
    $stmt = $connexion->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if ($user['password'] == 'default') {
            // Demander à l'admin de changer son mot de passe
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['change_password'] = true;
            header("Location: change_password.php");
            exit;
        }

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['prenom'] = $user['prenom'];
            header("Location: ../../index.php");
            exit;
        }
    }

    // Vérifier si c'est un CLIENT (table clients)
    $stmt = $connexion->prepare("SELECT * FROM clients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $client = $result->fetch_assoc();

    if ($client) {
        if ($client['password'] == 'default') {
            // Demander au client de changer son mot de passe
            $_SESSION['client_id'] = $client['id'];
            $_SESSION['prenom'] = $client['prenom'];
            $_SESSION['change_password'] = true;
            header("Location: ../auth/change_password.php");
            exit;
        }

        if (password_verify($password, $client['password'])) {
            $_SESSION['client_id'] = $client['id'];
            $_SESSION['prenom'] = $client['prenom'];
            header("Location: ../../client/index.php");
            exit;
        }
    }

    // Si aucun des deux, afficher une erreur
    $error = "❌ Identifiants incorrects.";
}
?>
