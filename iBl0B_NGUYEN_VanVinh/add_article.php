<?php
session_start();
require 'inc/database.php';

// Vérifiez que l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: connexion.php');
    exit();
}

$id_utilisateur = $_SESSION['id_utilisateur'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $id_categorie = $_POST['id_categorie'];
    $image = $_FILES['image']['name'];

    // Déplacez l'image téléchargée vers le dossier des images
    if (!empty($image)) {
        $target = 'images/' . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    // Insérez l'article dans la base de données
    $sql = "INSERT INTO articles (titre, contenu, image, id_utilisateur, id_categorie) VALUES (:titre, :contenu, :image, :id_utilisateur, :id_categorie)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'titre' => $titre,
        'contenu' => $contenu,
        'image' => $image,
        'id_utilisateur' => $id_utilisateur,
        'id_categorie' => $id_categorie
    ]);

    // Obtenez l'ID de l'article inséré
    $id_article = $pdo->lastInsertId();

    // Insérez l'entrée dans la table article_categorie
    $sql = "INSERT INTO article_categorie (id_article, id_categorie) VALUES (:id_article, :id_categorie)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id_article' => $id_article,
        'id_categorie' => $id_categorie
    ]);

    header('Location: mes_articles.php');
    exit();
}

// Récupérer les catégories pour le formulaire
$sql = "SELECT * FROM categories";
$stmt = $pdo->query($sql);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<link href="images/favicon.png" rel="icon">
    <title>Ajouter un article</title>
</head>
<body>
    <?php include 'inc/header.php'; ?>
    <div class="container">
        <h1>Ajouter un Article</h1>
        <form method="POST" action="add_article.php" enctype="multipart/form-data">
            <label for="titre">Titre :</label><br>
            <input type="text" id="titre" name="titre" required><br><br>
            <label for="contenu">Contenu :</label><br>
            <textarea id="contenu" name="contenu" rows="4" required></textarea><br><br>
            <label for="image">Image :</label><br>
            <input type="file" id="image" name="image"><br><br>
            <label for="id_categorie">Catégorie :</label><br>
            <select id="id_categorie" name="id_categorie" required>
                <option value="">Sélectionnez une catégorie</option>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?= $categorie['id'] ?>"><?= htmlspecialchars($categorie['nom']) ?></option>
                <?php endforeach; ?>
            </select><br><br>
            <button type="submit">Ajouter l'article</button>
        </form>
    </div>
    <?php include 'inc/footer.php'; ?>
</body>
</html>
