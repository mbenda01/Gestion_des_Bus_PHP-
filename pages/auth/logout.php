<?php
session_start();
session_destroy();
header("Location: /Gestion_des_bus_PHP/pages/auth/login.php");
exit;
?>
