// database.php
<?php
$serveur = "localhost";
$user = "root";
$pwd = "";
$dbname = "gestionbusphp";

$connexion = new mysqli($serveur, $user, $pwd, $dbname);

if ($connexion->connect_error) {
    die("Erreur de connexion : " . $connexion->connect_error);
}
?>
