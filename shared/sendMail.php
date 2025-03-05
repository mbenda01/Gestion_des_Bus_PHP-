<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require dirname(__DIR__) . '/vendor/autoload.php'; // Charger PHPMailer

function sendEmail($email, $prenom, $nom, $login, $password) {
    $mail = new PHPMailer(true);

    try {
        // Configurer Mailtrap
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = 'c9c76c1cc22934'; // Ton Mailtrap Username
        $mail->Password = 'c2f0d4cdac211c'; // Ton Mailtrap Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Activer TLS
        $mail->Port = 2525; // Port Mailtrap

        // Configurer l'expéditeur et le destinataire
        $mail->setFrom('noreply@gestionbus.com', 'Gestion des Bus');
        $mail->addAddress($email, "$prenom $nom");

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Bienvenue sur Gestion des Bus - Vos Identifiants';
        $mail->Body = "
            <h2>Bienvenue, $prenom $nom !</h2>
            <p>Votre compte a été créé avec succès.</p>
            <p><strong>Login :</strong> $login</p>
            <p><strong>Mot de passe :</strong> $password</p>
            <p><em>Veuillez changer votre mot de passe après votre première connexion.</em></p>
            <br>
            <p>Merci d'utiliser notre plateforme ! 🚍</p>
        ";

        // Envoyer l'email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
