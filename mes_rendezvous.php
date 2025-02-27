<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login_register.php");
    exit;
}

$utilisateur_id = $_SESSION['user_id'];

// Récupérer les rendez-vous de l'utilisateur
$stmt = $pdo->prepare("
    SELECT r.id, c.date, c.heure 
    FROM rendezvous r 
    JOIN creneaux c ON r.creneau_id = c.id 
    WHERE r.utilisateur_id = ?
");
$stmt->execute([$utilisateur_id]);
$rendezvous = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Rendez-vous</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Mes Rendez-vous</h2>
        <?php if (empty($rendezvous)): ?>
            <p>Aucun rendez-vous pris.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rendezvous as $rdv): ?>
                        <tr>
                            <td><?= htmlspecialchars($rdv['date']) ?></td>
                            <td><?= htmlspecialchars($rdv['heure']) ?></td>
                            <td>
                                <form action="annuler_rdv.php" method="post" onsubmit="return confirm('Voulez-vous vraiment annuler ce rendez-vous ?');">
                                    <input type="hidden" name="rendezvous_id" value="<?= $rdv['id'] ?>">
                                    <button type="submit" class="btn btn-danger">Annuler</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <p><a href="mon_compte.php" class="btn btn-secondary">Retour</a></p>
    </div>
</body>
</html>
