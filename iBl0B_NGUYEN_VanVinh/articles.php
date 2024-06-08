<?php
session_start();
require 'inc/database.php';

// Récupérer les articles de la base de données
$sql = "SELECT articles.*, utilisateurs.nom AS utilisateur_nom, utilisateurs.prenom AS utilisateur_prenom, categories.nom AS categorie_nom 
        FROM articles 
        JOIN utilisateurs ON articles.id_utilisateur = utilisateurs.id 
        JOIN categories ON articles.id_categorie = categories.id 
        ORDER BY date_publication DESC";
$stmt = $pdo->query($sql);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<link href="images/favicon.png" rel="icon">
    <title>Articles</title>
</head>
<body>
    <?php include 'inc/header.php'; ?>
    <div class="container">
        <h1>Articles</h1>
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $article): ?>
                <div class="article">
                    <h2><?= htmlspecialchars($article['titre']) ?></h2>
                    <p><?= htmlspecialchars($article['contenu']) ?></p>
                    <?php if (!empty($article['image'])): ?>
                        <img src="<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['titre']) ?>">
                    <?php endif; ?>
                    <div class="article-info">
                        <p>Catégorie : <?= htmlspecialchars($article['categorie_nom']) ?></p>
                        <p>Publié par <?= htmlspecialchars($article['utilisateur_nom']) . ' ' . htmlspecialchars($article['utilisateur_prenom']) ?> le <?= htmlspecialchars($article['date_publication']) ?></p>
                        <a href="article.php?id=<?= $article['id'] ?>">Lire la suite</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun article trouvé.</p>
        <?php endif; ?>
    </div>
    <?php include 'inc/footer.php'; ?>
</body>
</html>
