<!-- payment.php -->
<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Processus de paiement avec les options Wave ou Orange Money
    $payment_method = $_POST['payment_method'];
    // Logique de traitement du paiement ici
    echo "Paiement effectué via: " . htmlspecialchars($payment_method);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Méthode de Paiement</h2>

        <form method="POST">
            <label>Paiement via:</label>
            <select class="form-select" name="payment_method">
                <option value="wave">Wave</option>
                <option value="om">Orange Money</option>
            </select>
            <button type="submit" class="btn btn-success mt-3">Confirmer le paiement</button>
        </form>
    </div>
</body>
</html>
