<?php
session_start();

// Générer un token CSRF s'il n'existe pas encore
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$nom = $email = $message = "";
$nom_err = $email_err = $message_err = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification du token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Erreur CSRF, veuillez réessayer.");
    }

    // Vérification du champ honeypot (doit être vide)
    if (!empty($_POST['honeypot'])) {
        die("Spam détecté !");
    }

    // Vérification du nom
    if (empty(trim($_POST["nom"]))) {
        $nom_err = "Veuillez entrer votre nom.";
    } else {
        $nom = htmlspecialchars($_POST["nom"]);
    }

    // Vérification de l'email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Veuillez entrer votre adresse email.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Format d'email invalide.";
    } else {
        $email = htmlspecialchars($_POST["email"]);
    }

    // Vérification du message
    if (empty(trim($_POST["message"]))) {
        $message_err = "Veuillez entrer un message.";
    } else {
        $message = htmlspecialchars($_POST["message"]);
    }

    // Si tout est valide, envoi du mail sécurisé
    if (empty($nom_err) && empty($email_err) && empty($message_err)) {
        $to = "admin@example.com"; // Remplace par ton email
        $subject = "Nouveau message de contact";
        $body = "Nom: $nom\nEmail: $email\n\nMessage:\n$message";
        $headers = "From: " . filter_var($email, FILTER_SANITIZE_EMAIL);

        if (mail($to, $subject, $body, $headers)) {
            $success = "Votre message a été envoyé avec succès.";
            $nom = $email = $message = ""; // Réinitialiser le formulaire
        } else {
            $success = "Erreur lors de l'envoi du message.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg p-4 rounded-4">
            <h2 class="text-center mb-4">📩 Contactez-nous</h2>

            <?php if (!empty($success)): ?>
                <div class="alert alert-<?= strpos($success, 'succès') !== false ? 'success' : 'danger' ?> text-center">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <form action="contact.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                
                <!-- Champ Honeypot (pour bloquer les robots spammeurs) -->
                <input type="text" name="honeypot" style="display: none;">
                
                <div class="mb-3">
                    <label for="nom" class="form-label">👤 Nom :</label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($nom) ?>">
                    <small class="text-danger"><?= $nom_err ?></small>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">📧 Email :</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>">
                    <small class="text-danger"><?= $email_err ?></small>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">💬 Message :</label>
                    <textarea name="message" class="form-control"><?= htmlspecialchars($message) ?></textarea>
                    <small class="text-danger"><?= $message_err ?></small>
                </div>

                <button type="submit" class="btn btn-primary w-100">Envoyer</button>
            </form>

            <div class="text-center mt-3">
                <a href="mon_compte.php" class="btn btn-secondary">Retour</a>
            </div>
        </div>
    </div>
</body>
</html>
