<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Accès refusé.");
}

$utilisateur_id = $_SESSION['user_id'];

try {
    // Démarrer une transaction pour éviter des suppressions partielles
    $pdo->beginTransaction();

    // 1. Rendre disponibles les créneaux associés aux rendez-vous de l'utilisateur
    $stmt = $pdo->prepare("UPDATE creneaux 
                           SET disponible = 1 
                           WHERE id IN (SELECT creneau_id FROM rendezvous WHERE utilisateur_id = ?)");
    $stmt->execute([$utilisateur_id]);

    // 2. Supprimer tous les rendez-vous de l'utilisateur
    $stmt = $pdo->prepare("DELETE FROM rendezvous WHERE utilisateur_id = ?");
    $stmt->execute([$utilisateur_id]);

    // 3. Supprimer l'utilisateur
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
    $stmt->execute([$utilisateur_id]);

    // 4. Confirmer les changements
    $pdo->commit();

    // Détruire la session et rediriger vers l'accueil
    session_destroy();
    header("Location: login_register.php?message=compte_supprime");
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    die("Erreur lors de la suppression du compte : " . $e->getMessage());
}
?>