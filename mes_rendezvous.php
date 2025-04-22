<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Rendez-vous</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Mes Rendez-vous</h2>

        <!-- Message si aucun rendez-vous -->
        <?php if (empty($rendezvous)): ?>
            <div class="alert alert-warning text-center" role="alert">
                Vous n'avez aucun rendez-vous pris pour le moment.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
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
                                    <form id="cancel-form-<?= $rdv['id'] ?>" action="annuler_rdv.php" method="post">
                                        <input type="hidden" name="rendezvous_id" value="<?= $rdv['id'] ?>">
                                        <button type="button" class="btn btn-danger" onclick="confirmAnnulation(<?= $rdv['id'] ?>)">
                                            <i class="bi bi-x-circle"></i> Annuler
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- Bouton retour -->
        <p class="text-center">
            <a href="mon_compte.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Retour</a>
        </p>
    </div>

    <script>
        async function confirmAnnulation(rendezvousId) {
            const result = await Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, annuler!',
                cancelButtonText: 'Non, garder mon rendez-vous',
            });

            if (result.isConfirmed) {
                // Si l'utilisateur confirme, on soumet le formulaire correspondant
                const form = document.getElementById('cancel-form-' + rendezvousId);
                form.submit(); // Soumettre le formulaire pour annuler le rendez-vous
            }
        }
    </script>
</body>
</html>
