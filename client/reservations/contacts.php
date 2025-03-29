<?php
session_start();

if (!isset($_SESSION['client_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$prenom = $_SESSION['prenom'] ?? 'Client';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nous Contacter - TransitFlow</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #007bff, #00BFFF);
            overflow-x: hidden;
        }

        .main-content {
            margin-left: 240px;
            padding: 40px;
            background-color: white;
            border-radius: 15px;
            min-height: 100vh;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .contact-card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .social-icons a {
            margin: 10px;
            font-size: 1.8rem;
            color: #007bff;
            text-decoration: none;
            transition: transform 0.3s, color 0.3s;
        }

        .social-icons a:hover {
            transform: scale(1.2);
            color: #00BFFF;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .back-btn {
            display: block;
            margin: 20px auto;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            transition: color 0.3s;
        }

        .back-btn:hover {
            color: #00BFFF;
            text-decoration: underline;
        }

        .form-control {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../sidebar.php'; ?>

        <div class="main-content w-100">
            <div class="contact-card">
                <h2 class="text-primary mb-4">📞 Nous Contacter</h2>
                <p>Pour toute question ou assistance, contactez-nous via notre formulaire ou rejoignez-nous sur nos réseaux sociaux.</p>
                
                <!-- Formulaire de Contact -->
                <form method="POST" action="send_message.php">
                    <div class="mb-3">
                        <label for="name" class="form-label">Votre nom</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Votre email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Votre message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer le message</button>
                </form>

                <!-- Réseaux sociaux -->
                <div class="social-icons mt-4">
                    <a href="https://www.instagram.com/TransitFlow" target="_blank"><i class="bi bi-instagram"></i> Instagram</a>
                    <a href="https://www.facebook.com/TransitFlow" target="_blank"><i class="bi bi-facebook"></i> Facebook</a>
                    <a href="https://www.youtube.com/TransitFlow" target="_blank"><i class="bi bi-youtube"></i> YouTube</a>
                    <a href="https://www.twitter.com/TransitFlow" target="_blank"><i class="bi bi-twitter"></i> Twitter</a>
                </div>

                <!-- Bouton Retour au Tableau de Bord -->
                <a href="../index.php" class="back-btn">🏠 Retour au Tableau de Bord</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
</body>
</html>
