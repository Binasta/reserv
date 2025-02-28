<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Vous devez être connecté pour prendre un rendez-vous.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['date']) || !isset($_POST['creneaux'])) {
        die("Erreur : Aucun créneau ou date sélectionné.");
    }

    $utilisateur_id = $_SESSION['user_id'];
    $date = $_POST['date']; // La date sélectionnée
    $creneaux_id = (int) $_POST['creneaux']; // L'ID du créneau sélectionné

    // Récupérer l'heure du créneau sélectionné
    $stmt = $pdo->prepare("SELECT heure FROM creneaux WHERE id = ?");
    $stmt->execute([$creneaux_id]);
    $creneau = $stmt->fetch();

    if (!$creneau) {
        die("Ce créneau n'existe pas.");
    }

    // Combiner la date et l'heure sélectionnées pour créer la date complète
    $date_heure = $date . ' ' . $creneau['heure']; // Format 'YYYY-MM-DD HH:MM:SS'

    // Vérifier si ce créneau est encore disponible
$stmt = $pdo->prepare("SELECT * FROM creneaux WHERE id = ? AND disponible = 1");
$stmt->execute([$creneaux_id]);
$creneaux_dispo = $stmt->fetch();

if (!$creneaux_dispo) {
    die("Ce créneau n'est plus disponible.");
}

    // Insérer le rendez-vous
    $stmt = $pdo->prepare("INSERT INTO rendezvous (utilisateur_id, creneau_id, date_heure) VALUES (?, ?, ?)");
    $stmt->execute([$utilisateur_id, $creneaux_id, $date_heure]);

    // Mettre à jour le créneau pour qu'il ne soit plus disponible
    $stmt = $pdo->prepare("UPDATE creneaux SET disponible = 0, date_heure = ? WHERE id = ?");
    $stmt->execute([$date_heure, $creneaux_id]);

    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        let notif = document.createElement('div');
        notif.innerText = '✅ Rendez-vous pris avec succès !';
        notif.style.position = 'fixed';
        notif.style.top = '20px';
        notif.style.left = '50%';
        notif.style.transform = 'translateX(-50%)';
        notif.style.backgroundColor = '#28a745';
        notif.style.color = 'white';
        notif.style.padding = '10px 20px';
        notif.style.borderRadius = '5px';
        notif.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
        notif.style.zIndex = '1000';
        document.body.appendChild(notif);
        
        setTimeout(() => {
            notif.remove();
            window.location.href = 'prendre_rdv.php'; // Redirection après 3 sec
        }, 500);
    });
</script>";
exit; // Empêche l'exécution du reste du script
}
?>
