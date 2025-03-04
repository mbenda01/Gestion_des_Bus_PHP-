<?php
require_once 'shared/navBar.php';
require_once 'database.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'listeBus':
        require_once 'pages/bus/liste.php';
        break;
    case 'listeConducteurs':
        require_once 'pages/conducteurs/liste.php';
        break;
    case 'listeLignes':
        require_once 'pages/lignes/liste.php';
        break;
    case 'logout':
        require_once 'pages/auth/logout.php';
        break;
    default:
        echo '<h1 class="text-center">Bienvenue dans la gestion des bus</h1>';
}
?>
