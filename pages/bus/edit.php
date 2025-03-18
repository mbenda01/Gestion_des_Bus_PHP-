<?php
require_once dirname(__DIR__, 2) . '/database.php';

$error = "";
$success = "";
$id = $_GET['id'] ?? null;

// Vérifier si un ID valide est fourni
if (!$id || !is_numeric($id)) {
    header("Location: /Gestion_des_bus_PHP/index.php?action=listeBus&error=invalidID");
    exit;
}

// Récupérer les informations du bus
$stmt = $connexion->prepare("SELECT * FROM bus WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$bus = $result->fetch_assoc();

if (!$bus) {
    header("Location: /Gestion_des_bus_PHP/index.php?action=listeBus&error=notfound");
    exit;
}

// Récupérer toutes les lignes pour le `select`
$lignes = $connexion->query("SELECT id, numero FROM lignes")->fetch_all(MYSQLI_ASSOC);

// Mise à jour du bus si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $immatriculation = strtoupper(trim($_POST['immatriculation']));
    $type = $_POST['type'];
    $kilometrage = intval($_POST['kilometrage']);
    $nbre_place = intval($_POST['nbre_place']);
    $etat = $_POST['etat'];
    $ligne_id = intval($_POST['ligne_id']);
    $localisation = trim($_POST['localisation']);

    // Validation des entrées
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
    } elseif (empty($localisation)) {
        $error = "Veuillez entrer une localisation valide.";
    }

    // Mise à jour si aucune erreur
    if (empty($error)) {
        $stmt = $connexion->prepare("UPDATE bus 
                                     SET immatriculation = ?, type = ?, kilometrage = ?, nbre_place = ?, etat = ?, ligne_id = ?, localisation = ? 
                                     WHERE id = ?");
        $stmt->bind_param("ssiisisi", $immatriculation, $type, $kilometrage, $nbre_place, $etat, $ligne_id, $localisation, $id);
        
        if ($stmt->execute()) {
            $success = "✅ Bus mis à jour avec succès !";
            // Recharger les données du bus après modification
            $bus = [
                'immatriculation' => $immatriculation,
                'type' => $type,
                'kilometrage' => $kilometrage,
                'nbre_place' => $nbre_place,
                'etat' => $etat,
                'ligne_id' => $ligne_id,
                'localisation' => $localisation
            ];
        } else {
            $error = "❌ Erreur lors de la mise à jour du bus.";
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
    <title>Modifier un Bus</title>
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
        <h2 class="text-center text-primary">✏️ Modifier un Bus</h2>

        <!-- Affichage des messages -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form action="#" method="POST" onsubmit="validateForm(event)">
            <div class="mb-3">
                <label for="immatriculation" class="form-label">Immatriculation</label>
                <input type="text" class="form-control" id="immatriculation" name="immatriculation" value="<?= htmlspecialchars($bus['immatriculation']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type de bus</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="Tata" <?= $bus['type'] == 'Tata' ? 'selected' : '' ?>>Tata</option>
                    <option value="Car Rapide" <?= $bus['type'] == 'Car Rapide' ? 'selected' : '' ?>>Car Rapide</option>
                    <option value="DDK" <?= $bus['type'] == 'DDK' ? 'selected' : '' ?>>DDK</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="ligne_id" class="form-label">Ligne associée</label>
                <select class="form-control" id="ligne_id" name="ligne_id" required>
                    <?php foreach ($lignes as $ligne): ?>
                        <option value="<?= $ligne['id'] ?>" <?= $bus['ligne_id'] == $ligne['id'] ? 'selected' : '' ?>>Ligne <?= $ligne['numero'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="localisation" class="form-label">Localisation</label>
                <input type="text" class="form-control" id="localisation" name="localisation" value="<?= htmlspecialchars($bus['localisation']) ?>" required>
            </div>
            <button type="submit" class="btn btn-warning w-100">Modifier</button>
        </form>
    </div>
</body>
</html>
