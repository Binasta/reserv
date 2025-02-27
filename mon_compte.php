<?php
include 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_register.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Bienvenue, <?= htmlspecialchars($user['nom']) ?>!</h2>
        <p>Email : <?= htmlspecialchars($user['email']) ?></p>
        <p><a href="mes_rendezvous.php" class="btn btn-primary">Mes Rendez-vous</a></p>
        <p><a href="prendre_rdv.php" class="btn btn-success">Prendre un rendez-vous</a></p>
        <p><a href="supprimer_compte.php" class="btn btn-danger">Supprimer mon compte</a></p>
        <p><a href="logout.php" class="btn btn-secondary">Déconnexion</a></p>
    </div>
</body>
</html>