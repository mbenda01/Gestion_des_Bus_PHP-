<?php
session_start();

if (!isset($_SESSION['client_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$filepath = '../../assets/fake_qr.jpg';

// Forcer le téléchargement
header('Content-Description: File Transfer');
header('Content-Type: image/jpg');
header('Content-Disposition: attachment; filename="ticket.jpg"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filepath));
readfile($filepath);
exit;
?>
