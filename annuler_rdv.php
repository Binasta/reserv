<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Accès refusé.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rendezvous_id'])) {
    $rendezvous_id = (int) $_POST['rendezvous_id'];

    // Récupérer le créneau associé
    $stmt = $pdo->prepare("SELECT creneau_id FROM rendezvous WHERE id = ? AND utilisateur_id = ?");
    $stmt->execute([$rendezvous_id, $_SESSION['user_id']]);
    $rdv = $stmt->fetch();

    if ($rdv) {
        // Supprimer le rendez-vous
        $stmt = $pdo->prepare("DELETE FROM rendezvous WHERE id = ?");
        $stmt->execute([$rendezvous_id]);

        // Remettre le créneau disponible
        $stmt = $pdo->prepare("UPDATE creneaux SET disponible = 1 WHERE id = ?");
        $stmt->execute([$rdv['creneau_id']]);

        echo "Rendez-vous annulé avec succès.";
    } else {
        echo "Rendez-vous introuvable.";
    }
}

header("Location: mes_rendezvous.php");
exit;
