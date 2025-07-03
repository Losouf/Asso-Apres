<?php
// Affiche les erreurs en développement
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Anti-bot
    if (!empty($_POST['website'])) {
        exit;
    }

    // Récupération sécurisée des champs
    $lastname = trim($_POST['lastname'] ?? '');
    $firstname = trim($_POST['firstname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation des champs
    if ($lastname === '' || $firstname === '' || $email === '' || $subject === '' || $message === '') {
        echo "<script>alert('Tous les champs sont obligatoires.'); window.history.back();</script>";
        exit;
    }

    // Validation de l'adresse email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Adresse email invalide.'); window.history.back();</script>";
        exit;
    }

    // Infos mail
    $to = "apres.asso01@gmail.com";
    $from = "no-reply@association-apres.fr";
    $mail_subject = "Message de $firstname $lastname : $subject";
    $message_html = nl2br(htmlspecialchars($message));

    $body = "
    <html>
    <head>
      <meta charset='UTF-8'>
      <title>$mail_subject</title>
    </head>
    <body>
      <h2>Message de contact</h2>
      <p><strong>Nom :</strong> $lastname</p>
      <p><strong>Prénom :</strong> $firstname</p>
      <p><strong>Email :</strong> $email</p>
      <p><strong>Sujet :</strong> $subject</p>
      <p><strong>Message :</strong><br>$message_html</p>
    </body>
    </html>
    ";

    // En-têtes
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: $from\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Envoi
    if (mail($to, $mail_subject, $body, $headers)) {
        echo "<script>alert('✅ Message envoyé avec succès !'); window.location.href = 'https://association-apres.fr#Contact';</script>";
    } else {
        echo "<script>alert('❌ Erreur lors de l\'envoi.'); window.history.back();</script>";
    }

} else {
    // Accès direct interdit
    echo "Accès non autorisé.";
}
?>
