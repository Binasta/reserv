<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Veuillez vous connecter.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_rdv = $_POST['date_rdv'];
    $heure_rdv = $_POST['heure_rdv'];
    $user_id = $_SESSION['user_id'];

    // Vérifier la disponibilité du créneau
    $stmt = $pdo->prepare("SELECT id FROM rendezvous WHERE date_rdv = ? AND heure_rdv = ?");
    $stmt->execute([$date_rdv, $heure_rdv]);

    if ($stmt->rowCount() > 0) {
        echo "Ce créneau est déjà pris.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO rendezvous (utilisateur_id, date_rdv, heure_rdv) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $date_rdv, $heure_rdv]);
        echo "Rendez-vous réservé avec succès !";
    }
}
?>