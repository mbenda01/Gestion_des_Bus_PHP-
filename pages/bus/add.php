<?php
require_once dirname(__DIR__, 2) . '/database.php';

$error = "";
$success = "";

// Récupérer la liste des lignes pour l'association des bus
$lignes = $connexion->query("SELECT id, numero FROM lignes")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $immatriculation = strtoupper(trim($_POST['immatriculation']));
    $type = $_POST['type'];
    $kilometrage = intval($_POST['kilometrage']);
    $nbre_place = intval($_POST['nbre_place']);
    $etat = $_POST['etat'];
    $ligne_id = intval($_POST['ligne_id']);
    $localisation = trim($_POST['localisation']);

    // Vérification des champs
    if (!preg_match("/^[A-Z0-9-]+$/", $immatriculation)) {
        $error = "L'immatriculation ne doit contenir que des lettres, chiffres et tirets.";
    } elseif (!in_array($type, ['Tata', 'Car Rapide', 'DDK'])) {
        $error = "Type de bus invalide.";
    } elseif ($kilometrage < 0) {
        $error = "Le kilométrage ne peut pas être négatif.";
    } elseif ($nbre_place < 10 || $nbre_place > 100) {
        $error = "Le nombre de places doit être compris entre 10 et 100.";
    } elseif (!in_array($etat, ['En circulation', 'Hors circulation', 'En panne'])) {
        $error = "État du bus invalide.";
    } elseif (empty($ligne_id) || !is_numeric($ligne_id)) {
        $error = "Veuillez sélectionner une ligne valide.";
    }

    // Enregistrement si aucune erreur
    if (empty($error)) {
        $stmt = $connexion->prepare("INSERT INTO bus (immatriculation, type, kilometrage, nbre_place, etat, ligne_id, localisation) 
                                     VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiisis", $immatriculation, $type, $kilometrage, $nbre_place, $etat, $ligne_id, $localisation);
        if ($stmt->execute()) {
            $success = "✅ Bus ajouté avec succès !";
        } else {
            $error = "❌ Erreur lors de l'ajout du bus.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
    </style>
    <script>
        function validateForm(event) {
            let immatriculation = document.getElementById('immatriculation').value.trim();
            let kilometrage = document.getElementById('kilometrage').value;
            let nbre_place = document.getElementById('nbre_place').value;
            let localisation = document.getElementById('localisation').value.trim();

            let regexImmatriculation = /^[A-Z0-9-]+$/;
            if (!regexImmatriculation.test(immatriculation)) {
                alert("L'immatriculation doit contenir uniquement des lettres, chiffres et tirets.");
                event.preventDefault();
                return false;
            }
            if (kilometrage < 0) {
                alert("Le kilométrage ne peut pas être négatif.");
                event.preventDefault();
                return false;
            }
            if (nbre_place < 10 || nbre_place > 100) {
                alert("Le nombre de places doit être compris entre 10 et 100.");
                event.preventDefault();
                return false;
            }
            if (localisation.length === 0) {
                alert("Veuillez entrer une localisation valide.");
                event.preventDefault();
                return false;
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2 class="text-center text-primary">🚍 Ajouter un Bus</h2>

        <!-- Affichage des messages -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form action="#" method="POST" onsubmit="validateForm(event)">
            <div class="mb-3">
                <label for="immatriculation" class="form-label">Immatriculation</label>
                <input type="text" class="form-control" id="immatriculation" name="immatriculation" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type de bus</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="Tata">Tata</option>
                    <option value="Car Rapide">Car Rapide</option>
                    <option value="DDK">DDK</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="kilometrage" class="form-label">Kilométrage</label>
                <input type="number" class="form-control" id="kilometrage" name="kilometrage" required>
            </div>
            <div class="mb-3">
                <label for="nbre_place" class="form-label">Nombre de places</label>
                <input type="number" class="form-control" id="nbre_place" name="nbre_place" required>
            </div>
            <div class="mb-3">
                <label for="etat" class="form-label">État du bus</label>
                <select class="form-control" id="etat" name="etat" required>
                    <option value="En circulation">En circulation</option>
                    <option value="Hors circulation">Hors circulation</option>
                    <option value="En panne">En panne</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="ligne_id" class="form-label">Ligne associée</label>
                <select class="form-control" id="ligne_id" name="ligne_id" required>
                    <option value="">-- Sélectionner une ligne --</option>
                    <?php foreach ($lignes as $ligne): ?>
                        <option value="<?= $ligne['id'] ?>">Ligne <?= $ligne['numero'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="localisation" class="form-label">Localisation</label>
                <input type="text" class="form-control" id="localisation" name="localisation" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ajouter</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
