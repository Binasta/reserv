<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Générer un token CSRF s'il n'existe pas
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prendre un Rendez-vous</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg p-4 rounded-4" style="max-width: 500px; width: 100%;">
            <div class="card-body">
                <h2 class="text-center mb-4"><i class="fas fa-calendar-plus text-success"></i> Prendre un Rendez-vous</h2>

                <form id="rdv-form" action="traitement_rdv.php" method="post">
                    <!-- Token CSRF caché -->
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

                    <div class="mb-3">
                        <label for="date" class="form-label">Choisir une date :</label>
                        <input type="date" id="date" name="date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="creneaux" class="form-label">Choisir un créneau :</label>
                        <select id="creneaux" name="creneaux" class="form-control" required>
                            <option value="">Sélectionnez une date d'abord</option>
                        </select>
                        <div id="loading" class="text-center mt-2" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Chargement...
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100" id="submit-btn">
                        <i class="fas fa-check-circle"></i> Réserver
                    </button>
                </form>

                <a href="mon_compte.php" class="btn btn-secondary w-100 mt-3"><i class="fas fa-arrow-left"></i> Retour</a>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#date').change(function() {
                var selectedDate = $(this).val();
                $('#loading').show();
                $.ajax({
                    url: 'get_disponibilites.php',
                    type: 'POST',
                    data: { date: selectedDate, csrf_token: '<?= $_SESSION['csrf_token']; ?>' },
                    success: function(data) {
                        $('#creneaux').html(data);
                        $('#loading').hide();
                    },
                    error: function() {
                        alert("Une erreur est survenue lors du chargement des créneaux.");
                        $('#loading').hide();
                    }
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("rdv-form");
            const submitButton = document.getElementById("submit-btn");

            form.addEventListener("submit", function (event) {
                submitButton.disabled = true; // Désactiver le bouton après le premier clic
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Réservation en cours...';
                setTimeout(() => {
                    submitButton.disabled = false; 
                    submitButton.innerHTML = '<i class="fas fa-check-circle"></i> Réserver';
                }, 5000);
            });
        });
    </script>

</body>
</html>
