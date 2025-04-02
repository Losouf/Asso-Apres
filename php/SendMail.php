<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!empty($_POST['website'])) {
      exit;
    }

    $fields = ['lastname', 'firstname', 'email', 'subject', 'message'];
    $data = [];

    foreach ($fields as $field) {
        $data[$field] = trim(htmlspecialchars($_POST[$field] ?? ''));
    }

    if (in_array('', $data)) {
        echo "<script>
            alert('Tous les champs sont obligatoires. Veuillez compléter le formulaire.');
            window.history.back();
        </script>";
        exit;
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo "<script>
            alert('Adresse email invalide.');
            window.history.back();
        </script>";
        exit;
    }

    $from = "mail_php@association-apres.fr";
    $to = "apres.asso01@gmail.com";
    $message_html = nl2br($data['message']);
    $mail_subject = "Nouveau message de contact de {$data['firstname']} {$data['lastname']}";

    $body = "
    <html>
    <head>
      <title>Nouveau message</title>
      <style>
        body { font-family: 'Inter', sans-serif; background: #f9f9f9; color: #333; }
        .container { padding: 20px; background: #fff; border-radius: 10px; }
        h2 { color: #000; }
        .footer { margin-top: 30px; font-size: 0.9em; color: #777; }
      </style>
    </head>
    <body>
      <div class='container'>
        <h2>Nouveau message de {$data['firstname']} {$data['lastname']}</h2>
        <p><strong>Nom :</strong> {$data['lastname']}</p>
        <p><strong>Prénom :</strong> {$data['firstname']}</p>
        <p><strong>Email :</strong> {$data['email']}</p>
        <p><strong>Sujet :</strong> {$data['subject']}</p>
        <p><strong>Message :</strong><br>$message_html</p>
      </div>
      <div class='footer'>
        <p>Ce message a été envoyé depuis votre site web <a href='https://association-apres.fr/'>association-apres.fr</a></p>
      </div>
    </body>
    </html>
    ";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: $from\r\n";
    $headers .= "Reply-To: {$data['email']}\r\n";

    if (mail($to, $mail_subject, $body, $headers)) {
        echo "<script>
            alert('Votre message a été envoyé avec succès.');
            window.location.href = 'https://association-apres.fr/';
        </script>";
    } else {
        echo "<script>
            alert('Une erreur est survenue lors de l\'envoi de l\'email.');
            window.history.back();
        </script>";
    }
}
?>
