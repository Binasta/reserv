<?php
include 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_register.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Prendre un Rendez-vous</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Prendre un Rendez-vous</h2>
        <form action="traitement_rdv.php" method="post">
            <label for="date">Choisir une date :</label>
            <input type="date" id="date" name="date" class="form-control" required><br>
            
            <label for="creneaux">Choisir un créneau :</label>
            <select id="creneaux" name="creneaux" class="form-control" required>
                <option value="">Sélectionnez une date d'abord</option>
            </select><br>
            
            <button type="submit" class="btn btn-success">Réserver</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#date').change(function() {
                var selectedDate = $(this).val();
                $.ajax({
                    url: 'get_disponibilites.php',
                    type: 'POST',
                    data: { date: selectedDate },
                    success: function(data) {
                        $('#creneaux').html(data);
                    }
                });
            });
        });
    </script>
</body>
</html>
