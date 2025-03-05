<?php
require_once dirname(__DIR__, 2) . '/database.php';
require_once dirname(__DIR__, 2) . '/shared/sendMail.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $role = $_POST['role'];
    $login = strtolower($prenom . '.' . $nom);
    $password = 'default';

    if (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $prenom)) {
        $error = "Le prénom ne doit contenir que des lettres.";
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $nom)) {
        $error = "Le nom ne doit contenir que des lettres.";
    } elseif (!preg_match("/^\d{9}$/", $telephone)) {
        $error = "Le numéro de téléphone doit contenir exactement 9 chiffres.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'email est invalide.";
    }

    if (empty($error)) {
        $stmt = $connexion->prepare("INSERT INTO users (prenom, nom, email, telephone, role, login, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $prenom, $nom, $email, $telephone, $role, $login, $password);

        if ($stmt->execute()) {
            if (sendEmail($email, $prenom, $nom, $login, $password)) {
                header("Location: /Gestion_des_bus_PHP/index.php?action=listeUsers&success=1");
                exit;
            } else {
                $error = "L'email n'a pas pu être envoyé.";
            }
        } else {
            $error = "Erreur lors de l'ajout de l'utilisateur.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script>
        function validateForm(event) {
            let prenom = document.getElementById('prenom').value.trim();
            let nom = document.getElementById('nom').value.trim();
            let email = document.getElementById('email').value.trim();
            let telephone = document.getElementById('telephone').value.trim();

            let regexNomPrenom = /^[a-zA-ZÀ-ÿ\s]+$/;
            let regexTelephone = /^\d{9}$/;
            let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!regexNomPrenom.test(prenom)) {
                alert("Le prénom ne doit contenir que des lettres.");
                event.preventDefault();
                return false;
            }
            if (!regexNomPrenom.test(nom)) {
                alert("Le nom ne doit contenir que des lettres.");
                event.preventDefault();
                return false;
            }
            if (!regexTelephone.test(telephone)) {
                alert("Le numéro de téléphone doit contenir exactement 9 chiffres.");
                event.preventDefault();
                return false;
            }
            if (!regexEmail.test(email)) {
                alert("L'email est invalide.");
                event.preventDefault();
                return false;
            }
        }
    </script>

</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center text-primary">Ajouter un utilisateur</h2>
        
        <!-- Affichage des erreurs -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form action="#" method="POST" class="shadow p-4 rounded bg-light" onsubmit="validateForm(event)">
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" required>
            </div>
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="telephone" name="telephone" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="Responsable de Parc">Responsable de Parc</option>
                    <option value="Responsable de Trajet">Responsable de Trajet</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ajouter</button>
        </form>
    </div>
</body>
</html>
