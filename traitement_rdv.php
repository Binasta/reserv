<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Vous devez être connecté pour prendre un rendez-vous.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['creneau_id'])) {
        die("Erreur : Aucun créneau sélectionné.");
    }

    $utilisateur_id = $_SESSION['user_id'];
    $creneau_id = $_POST['creneau_id'];

    // Vérifier si le créneau existe et est disponible
    $stmt = $pdo->prepare("SELECT * FROM creneaux WHERE id = ? AND disponible = 1");
    $stmt->execute([$creneau_id]);
    $creneau = $stmt->fetch();

    if (!$creneau) {
        die("Ce créneau n'est plus disponible.");
    }

    // Insérer le rendez-vous
    $stmt = $pdo->prepare("INSERT INTO rendezvous (utilisateur_id, creneau_id) VALUES (?, ?)");
    $stmt->execute([$utilisateur_id, $creneau_id]);

    // Mettre à jour le créneau pour qu'il ne soit plus disponible
    $stmt = $pdo->prepare("UPDATE creneaux SET disponible = 0 WHERE id = ?");
    $stmt->execute([$creneau_id]);

    echo "Rendez-vous pris avec succès !";
}
?>
