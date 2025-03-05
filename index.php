<?php
session_start();
require_once 'database.php';

// Vérifier si l'utilisateur est connecté, sinon rediriger vers login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: pages/auth/login.php");
    exit;
}

// Vérifier si l'utilisateur doit changer son mot de passe
if (isset($_SESSION['change_password']) && $_SESSION['change_password'] === true) {
    header("Location: pages/auth/change_password.php");
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
    <style>
        body {
            background-color: #f8f9fa;
        }

        /* Sidebar fixée */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color:linear-gradient(135deg, #2980b9, #3498db);
            color: white;
            padding: 20px;
        }
        .sidebar a {
            text-decoration: none;
            color: white;
            display: block;
            padding: 10px;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Ajustement du contenu principal */
        .main-content {
            margin-left: 260px; /* Pour éviter le chevauchement */
            padding: 20px;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <?php require_once 'shared/sidebar.php'; ?>

    <!-- Contenu principal -->
    <div class="main-content">
        <div class="text-center mb-4">
            <h1 class="display-4 text-primary"> Gestion des Bus</h1>
            <p class="lead">Gérez facilement les bus, conducteurs, trajets et utilisateurs.</p>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <?php
                    $action = $_GET['action'] ?? 'home';

                    switch ($action) {
                        case 'listeBus':
                            require_once 'pages/bus/liste.php';
                            break;
                        case 'addBus':
                            require_once 'pages/bus/add.php';
                            break;
                        case 'editBus':
                            require_once 'pages/bus/edit.php';
                            break;

                        case 'listeConducteurs':
                            require_once 'pages/conducteurs/liste.php';
                            break;
                        case 'addConducteur':
                            require_once 'pages/conducteurs/add.php';
                            break;
                        case 'editConducteur':
                            require_once 'pages/conducteurs/edit.php';
                            break;

                        case 'listeLignes':
                            require_once 'pages/lignes/liste.php';
                            break;
                        case 'addLigne':
                            require_once 'pages/lignes/add.php';
                            break;
                        case 'editLigne':
                            require_once 'pages/lignes/edit.php';
                            break;

                        case 'listeUsers':
                            require_once 'pages/users/liste.php';
                            break;
                        case 'addUser':
                            require_once 'pages/users/add.php';
                            break;
                        case 'editUser':
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                $id = $_POST['id'] ?? null;
                                $prenom = trim($_POST['prenom']);
                                $nom = trim($_POST['nom']);
                                $email = trim($_POST['email']);
                                $telephone = trim($_POST['telephone']);
                                $role = $_POST['role'];

                                if ($id && is_numeric($id)) {
                                    $stmt = $connexion->prepare("UPDATE users SET prenom=?, nom=?, email=?, telephone=?, role=? WHERE id=?");
                                    $stmt->bind_param("sssssi", $prenom, $nom, $email, $telephone, $role, $id);
                                    $stmt->execute();
                                }
                                header("Location: index.php?action=listeUsers&success=edit");
                                exit;
                            }
                            break;

                        case 'deleteUser':
                            $id = $_GET['id'] ?? null;
                            if ($id && is_numeric($id)) {
                                $stmt = $connexion->prepare("DELETE FROM users WHERE id = ?");
                                $stmt->bind_param("i", $id);
                                $stmt->execute();
                            }
                            header("Location: index.php?action=listeUsers");
                            exit;
                            break;

                        case 'logout':
                            session_destroy();
                            header("Location: pages/auth/login.php");
                            exit;
                            break;

                        default:
                            echo '<h2 class="text-center">Bienvenue sur la plateforme de gestion des bus !</h2>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
