// change_password.php
<?php
session_start();
require_once '../../database.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['change_password']) {
    header("Location: ../../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $connexion->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $_SESSION['user_id']);
        $stmt->execute();

        $_SESSION['change_password'] = false;
        header("Location: ../../index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Changer le mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h3 class="text-primary">Changer le mot de passe</h3>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"> <?= $error ?> </div>
            <?php endif; ?>
            <form action="#" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe:</label>
                    <input type="password" class="form-control" name="new_password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmer le mot de passe:</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
            </form>
        </div>
    </div>
</body>
</html>
