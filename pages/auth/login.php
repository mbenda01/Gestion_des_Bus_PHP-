// login.php
<?php
session_start();
require_once '../../database.php'; // Assurez-vous que le fichier database.php contient la connexion à la BDD
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);

    if (empty($login) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $stmt = $connexion->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            $error = "Utilisateur introuvable.";
        } else {
            if ($user['password'] == 'default') {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['change_password'] = true;
                header("Location: change_password.php");
                exit;
            }

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['change_password'] = false;
                header("Location: ../../index.php");
                exit;
            } else {
                $error = "Mot de passe incorrect.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion des Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h3 class="text-primary">Connexion</h3>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"> <?= $error ?> </div>
            <?php endif; ?>
            <form action="#" method="POST">
                <div class="mb-3">
                    <label for="login" class="form-label">Identifiant</label>
                    <input type="text" class="form-control" id="login" name="login" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>
        </div>
    </div>
</body>
</html>

// change_password.php
<?php
session_start();
require_once '../../database.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['change_password']) {
    header("Location: ../../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $stmt = $connexion->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $new_password, $_SESSION['user_id']);
    $stmt->execute();
    $_SESSION['change_password'] = false;
    header("Location: ../../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Changer le mot de passe</title>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h3 class="text-primary">Changer le mot de passe</h3>
            <form action="#" method="POST">
                <div class="mb-3">
                    <label>Nouveau mot de passe:</label>
                    <input type="password" class="form-control" name="new_password" required>
                </div>
                <div class="mb-3">
                    <label>Confirmer le mot de passe:</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
            </form>
        </div>
    </div>
</body>
</html>
