<?php
session_start();
require 'inc/database.php';

// Vérifiez que l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: connexion.php');
    exit();
}

$id_utilisateur = $_SESSION['id_utilisateur'];

// Récupérer les articles de l'utilisateur connecté
$sql = "SELECT * FROM articles WHERE id_utilisateur = :id_utilisateur ORDER BY date_publication DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id_utilisateur' => $id_utilisateur]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<link href="images/favicon.png" rel="icon">
    <title>Mes articles</title>
</head>
<body>
    <?php include 'inc/header.php'; ?>
    <div class="container">
        <h1>Mes Articles</h1>
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $article): ?>
                <div class="article">
                    <h2><?= htmlspecialchars($article['titre']) ?></h2>
                    <p><?= htmlspecialchars($article['contenu']) ?></p>
                    <?php if (!empty($article['image'])): ?>
                        <img src="images/<?= htmlspecialchars(basename($article['image'])) ?>" alt="<?= htmlspecialchars($article['titre']) ?>">
                    <?php endif; ?>
                    <div class="article-info">
                        <p>Publié le <?= htmlspecialchars($article['date_publication']) ?></p>
                        <a href="modifier_article.php?id=<?= $article['id'] ?>">Modifier</a> |
                        <a href="inc/edit_article.php?action=supprimer&id=<?= $article['id'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet article ?');">Supprimer</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Vous n'avez pas encore publié d'articles.</p>
        <?php endif; ?>
    </div>
    <?php include 'inc/footer.php'; ?>
</body>
</html>
