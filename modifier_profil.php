<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login_register.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $date_naissance = $_POST['date_naissance'];
    $adresse = trim($_POST['adresse']);
    $telephone = trim($_POST['telephone']);
    $email = trim($_POST['email']);

    // Vérifier si l'email est déjà utilisé par un autre utilisateur
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? AND id != ?");
    $stmt->execute([$email, $user_id]);
    if ($stmt->fetch()) {
        $message = '<div class="alert alert-danger">Cet email est déjà utilisé.</div>';
    } else {
        // Mettre à jour les informations
        $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, date_naissance = ?, adresse = ?, telephone = ?, email = ? WHERE id = ?");
        if ($stmt->execute([$nom, $prenom, $date_naissance, $adresse, $telephone, $email, $user_id])) {
            $message = '<div class="alert alert-success">Profil mis à jour avec succès !</div>';
            // Recharger les nouvelles informations
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
        } else {
            $message = '<div class="alert alert-danger">Erreur lors de la mise à jour.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mon profil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg p-4 rounded-4" style="max-width: 600px; margin: auto;">
            <h2 class="mb-3 text-center">Modifier mon profil</h2>
            
            <?= $message; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date de naissance</label>
                    <input type="date" name="date_naissance" class="form-control" value="<?= htmlspecialchars($user['date_naissance']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Adresse postale</label>
                    <input type="text" name="adresse" class="form-control" value="<?= htmlspecialchars($user['adresse']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Numéro de téléphone</label>
                    <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($user['telephone']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Enregistrer les modifications</button>
            </form>

            <a href="mon_compte.php" class="btn btn-secondary w-100 mt-3">Retour</a>
        </div>
    </div>
</body>
</html>
