<?php
session_start();
require 'inc/database.php';
require 'inc/fonctions.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    if (connexion_utilisateur($pdo, $email, $mot_de_passe)) {
        header('Location: index.php');
        exit();
    } else {
        $erreur = 'Email ou mot de passe incorrect.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<link href="images/favicon.png" rel="icon">
    <title>Connexion</title>
</head>
<body>
    <?php include 'inc/header.php'; ?>
    <h1>Connexion</h1>
    <?php if ($erreur): ?>
        <p><?= $erreur ?></p>
    <?php endif; ?>
    <form method="POST" action="connexion.php">
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Mot de passe</label>
        <input type="password" name="mot_de_passe" required>
        <button type="submit">Connexion</button>
    </form>
    <?php include 'inc/footer.php'; ?>
</body>
</html>
