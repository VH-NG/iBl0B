<?php
session_start();
require 'inc/database.php';

// Récupérer toutes les catégories
$sql = "SELECT * FROM categories";
$stmt = $pdo->query($sql);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gérer la sélection de catégorie
$articles = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_categorie'])) {
    $id_categorie = $_POST['id_categorie'];

    // Récupérer les articles de la catégorie sélectionnée
    $sql = "SELECT * FROM articles WHERE id_categorie = :id_categorie";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_categorie' => $id_categorie]);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<link href="images/favicon.png" rel="icon">
    <title>Gérer les Catégories des Articles</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'inc/header.php'; ?>
    <div class="container">
        <h1>Gérer les Catégories des Articles</h1>
        <form method="POST" action="manage_categories.php">
            <label for="id_categorie">Sélectionnez une catégorie :</label>
            <select id="id_categorie" name="id_categorie" required>
                <option value="">Sélectionnez une catégorie</option>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?= $categorie['id'] ?>"><?= htmlspecialchars($categorie['nom']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Afficher les articles</button>
        </form>

        <?php if (!empty($articles)): ?>
            <h2>Articles dans la catégorie sélectionnée :</h2>
            <?php foreach ($articles as $article): ?>
                <div class="article">
                    <h3><?= htmlspecialchars($article['titre']) ?></h3>
                    <p><?= htmlspecialchars($article['contenu']) ?></p>
                    <?php if (!empty($article['image'])): ?>
                        <img src="images/<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['titre']) ?>">
                    <?php endif; ?>
                    <p>Publié le <?= htmlspecialchars($article['date_publication']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <p>Aucun article trouvé pour cette catégorie.</p>
        <?php endif; ?>
    </div>
    <?php include 'inc/footer.php'; ?>
</body>
</html>
