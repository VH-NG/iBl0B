<?php
require 'inc/database.php';
require 'inc/fonctions.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $result = creer_utilisateur($pdo, $nom, $prenom, $email, $mot_de_passe);
    if ($result === true) {
        header('Location: connexion.php');
        exit();
    } else {
        $erreur = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<link href="images/favicon.png" rel="icon">
<meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include 'inc/header.php'; ?>
    <h1>Inscription</h1>
    <?php if ($erreur): ?>
        <p style="color: red;"><?= $erreur ?></p>
    <?php endif; ?>
    <form action="inscription.php" method="post">
        <label>Nom</label>
        <input type="text" name="nom" required>
        <label>Prénom</label>
        <input type="text" name="prenom" required>
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Mot de passe</label>
        <input type="password" name="mot_de_passe" required>
        <button type="submit">Inscription</button>
    </form>
    <?php include 'inc/footer.php'; ?>
</body>
</html>
