<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion / Inscription</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Connexion</h2>
        <form action="connexion.php" method="post">
            <input type="email" name="email" class="form-control" placeholder="Email" required><br>
            <input type="password" name="mot_de_passe" class="form-control" placeholder="Mot de passe" required><br>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
        <hr>
        <h2>Inscription</h2>
        <form action="inscription.php" method="post">
            <input type="text" name="nom" class="form-control" placeholder="Nom" required><br>
            <input type="text" name="prenom" class="form-control" placeholder="Prénom" required><br>
            <input type="date" name="date_naissance" class="form-control" required><br>
            <input type="text" name="adresse" class="form-control" placeholder="Adresse" required><br>
            <input type="tel" name="telephone" class="form-control" placeholder="Téléphone" required><br>
            <input type="email" name="email" class="form-control" placeholder="Email" required><br>
            <input type="password" name="mot_de_passe" class="form-control" placeholder="Mot de passe" required><br>
            <button type="submit" class="btn btn-success">S'inscrire</button>
        </form>
    </div>
</body>
</html>