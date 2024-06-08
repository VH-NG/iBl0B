<?php
session_start();
require 'inc/database.php';

// Vérifiez que l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: connexion.php');
    exit();
}

$id_utilisateur = $_SESSION['id_utilisateur'];

// Récupérer les informations de l'utilisateur
$sql = "SELECT * FROM utilisateurs WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id_utilisateur]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        // Mettre à jour les informations de l'utilisateur
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'];

        // Vérifiez si l'email existe déjà (sauf pour l'utilisateur actuel)
        $sql = "SELECT COUNT(*) FROM utilisateurs WHERE email = :email AND id != :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email, 'id' => $id_utilisateur]);
        $email_exists = $stmt->fetchColumn();

        if ($email_exists) {
            $erreur = 'L\'email est déjà pris.';
        } else {
            $sql = "UPDATE utilisateurs SET nom = :nom, prenom = :prenom, email = :email";
            $params = [
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'id' => $id_utilisateur
            ];

            if (!empty($nouveau_mot_de_passe)) {
                $mot_de_passe_hash = password_hash($nouveau_mot_de_passe, PASSWORD_BCRYPT);
                $sql .= ", mot_de_passe = :mot_de_passe";
                $params['mot_de_passe'] = $mot_de_passe_hash;
            }

            $sql .= " WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            header('Location: profil.php');
            exit();
        }
    } elseif (isset($_POST['delete'])) {
        // Rediriger vers le script de suppression de l'utilisateur
        header('Location: inc/delete_user.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="images/favicon.png" rel="icon">
    <meta charset="UTF-8">
    <title>Profil</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'inc/header.php'; ?>
    <div class="container">
        <h1>Mon Profil</h1>
        <?php if (isset($erreur) && $erreur): ?>
            <p style="color: red;"><?= $erreur ?></p>
        <?php endif; ?>
        <form method="POST" action="profil.php">
            <label for="nom">Nom :</label><br>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required><br><br>

            <label for="prenom">Prénom :</label><br>
            <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($utilisateur['prenom']) ?>" required><br><br>

            <label for="email">Email :</label><br>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($utilisateur['email']) ?>" required><br><br>

            <label for="nouveau_mot_de_passe">Nouveau mot de passe (laisser vide pour ne pas changer) :</label><br>
            <input type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe"><br><br>

            <button type="submit" name="update">Mettre à jour</button>
        </form><br>
        <form method="POST" action="inc/delete_user.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.');">
            <button type="submit" name="delete">Supprimer le compte</button>
        </form>
    </div>
    <?php include 'inc/footer.php'; ?>
</body>
</html>
