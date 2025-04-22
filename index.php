<?php
session_start();

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
    <title>Connexion / Inscription</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            background: white;
            padding: 20px;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn-primary, .btn-success {
            border-radius: 10px;
            font-weight: bold;
        }
        .form-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: gray;
        }
        .form-group {
            position: relative;
        }
        .form-control {
            padding-left: 40px;
        }
        .nav-tabs .nav-link.active {
            background-color: #007bff;
            color: white;
            border-radius: 10px;
        }
        .nav-tabs .nav-link {
            border: none;
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <ul class="nav nav-tabs justify-content-center mb-3" id="authTab">
                        <li class="nav-item">
                            <a class="nav-link active" id="login-tab" data-bs-toggle="tab" href="#login">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="register-tab" data-bs-toggle="tab" href="#register">Inscription</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Connexion -->
                        <div class="tab-pane fade show active" id="login">
                            <h3 class="text-center mb-3"><i class="fas fa-sign-in-alt"></i> Connexion</h3>
                            <form action="connexion.php" method="post">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

                                <div class="mb-3 form-group">
                                    <i class="fas fa-envelope form-icon"></i>
                                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                                </div>

                                <div class="mb-3 form-group">
                                    <i class="fas fa-lock form-icon"></i>
                                    <input type="password" name="mot_de_passe" class="form-control" placeholder="Mot de passe" required>
                                </div>

                                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-sign-in-alt"></i> Se connecter</button>
                            </form>
                        </div>

                        <!-- Inscription -->
                        <div class="tab-pane fade" id="register">
                            <h3 class="text-center mb-3"><i class="fas fa-user-plus"></i> Inscription</h3>
                            <form action="inscription.php" method="post">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

                                <div class="row">
                                    <div class="col-md-6 mb-3 form-group">
                                        <i class="fas fa-user form-icon"></i>
                                        <input type="text" name="nom" class="form-control" placeholder="Nom" required>
                                    </div>
                                    <div class="col-md-6 mb-3 form-group">
                                        <i class="fas fa-user form-icon"></i>
                                        <input type="text" name="prenom" class="form-control" placeholder="Prénom" required>
                                    </div>
                                </div>

                                <div class="mb-3 form-group">
                                    <i class="fas fa-calendar form-icon"></i>
                                    <input type="date" name="date_naissance" class="form-control" required>
                                </div>

                                <div class="mb-3 form-group">
                                    <i class="fas fa-map-marker-alt form-icon"></i>
                                    <input type="text" name="adresse" class="form-control" placeholder="Adresse" required>
                                </div>

                                <div class="mb-3 form-group">
                                    <i class="fas fa-phone form-icon"></i>
                                    <input type="tel" name="telephone" class="form-control" placeholder="Téléphone" required pattern="[0-9]{10}">
                                </div>

                                <div class="mb-3 form-group">
                                    <i class="fas fa-envelope form-icon"></i>
                                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                                </div>

                                <div class="mb-3 form-group">
                                    <i class="fas fa-lock form-icon"></i>
                                    <input type="password" name="mot_de_passe" class="form-control" placeholder="Mot de passe" required minlength="6">
                                </div>

                                <button type="submit" class="btn btn-success w-100"><i class="fas fa-user-plus"></i> S'inscrire</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
