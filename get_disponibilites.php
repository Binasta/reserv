<?php
include 'config.php';

if (isset($_POST['date'])) {
    $date = $_POST['date'];

    // Vérification si la date est bien reçue
    error_log("Date reçue : " . $date);

    $stmt = $pdo->prepare("SELECT id, heure FROM creneaux WHERE date = ? AND disponible = 1");
    $stmt->execute([$date]);
    $creneaux = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($creneaux) {
        foreach ($creneaux as $creneau) {
            echo "<option value='" . htmlspecialchars($creneau['id']) . "'>" . htmlspecialchars($creneau['heure']) . "</option>";
        }
    } else {
        echo "<option value=''>Aucun créneau disponible</option>";
    }
} else {
    echo "<option value=''>Erreur : date non reçue</option>";
}
?>
