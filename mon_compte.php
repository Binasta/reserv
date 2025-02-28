<?php
include 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_register.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .card {
            border-radius: 15px;
        }
        .btn:hover {
            transform: scale(1.05);
            transition: 0.3s;
        }
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
    </style>
</head>
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg p-4 rounded-4 text-center" style="max-width: 500px; width: 100%;">
            <div class="card-body">
                <img src="default_profile.png" alt="Profil" class="profile-pic">
                <h2 class="mb-3"><i class="fas fa-user-circle text-primary"></i> Bienvenue, <?= htmlspecialchars($user['nom']) ?> !</h2>
                <p class="text-muted"><i class="fas fa-envelope"></i> <?= htmlspecialchars($user['email']) ?></p>
                
                <div class="d-grid gap-3 my-4">
                    <a href="mes_rendezvous.php" class="btn btn-primary"><i class="fas fa-calendar-alt"></i> Mes Rendez-vous</a>
                    <a href="prendre_rdv.php" class="btn btn-success"><i class="fas fa-plus-circle"></i> Prendre un Rendez-vous</a>
                    <a href="modifier_profil.php" class="btn btn-warning"><i class="fas fa-user-edit"></i> Modifier mon profil</a>
                    <a href="contact.php" class="btn btn-info"><i class="fas fa-envelope"></i> Contact</a> <!-- Lien ajouté -->
                </div>

                <button class="btn btn-danger w-100" onclick="confirmDelete()"><i class="fas fa-trash-alt"></i> Supprimer mon compte</button>
                <a href="logout.php" class="btn btn-secondary w-100 mt-3"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'supprimer_compte.php';
                }
            });
        }
    </script>

</body>
</html>
