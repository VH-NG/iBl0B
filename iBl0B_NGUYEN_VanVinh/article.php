<?php
session_start();
require 'inc/database.php';

// Vérifiez que l'ID de l'article est passé dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID d'article non spécifié.";
    exit();
}

$id_article = $_GET['id'];

// Récupérer l'article en fonction de l'ID avec LEFT JOIN pour gérer les catégories NULL
$sql = "SELECT articles.*, utilisateurs.nom AS utilisateur_nom, utilisateurs.prenom AS utilisateur_prenom, categories.nom AS categorie_nom 
        FROM articles 
        JOIN utilisateurs ON articles.id_utilisateur = utilisateurs.id 
        LEFT JOIN categories ON articles.id_categorie = categories.id 
        WHERE articles.id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id_article]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifiez si l'article a été trouvé
if (!$article) {
    echo "Article non trouvé avec l'ID: " . htmlspecialchars($id_article);
    exit();
}

// Récupérer les commentaires pour cet article
$sql = "SELECT * FROM commentaires WHERE id_article = :id_article ORDER BY date_commentaire DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id_article' => $id_article]);
$commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<link href="images/favicon.png" rel="icon">
    <title><?= htmlspecialchars($article['titre']) ?></title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <?php include 'inc/header.php'; ?>
    <div class="container">
        <h1><?= htmlspecialchars($article['titre']) ?></h1>
        <p><?= nl2br(htmlspecialchars($article['contenu'])) ?></p>
        <?php if (!empty($article['image'])): ?>
            <img src="images/<?= htmlspecialchars(basename($article['image'])) ?>" alt="<?= htmlspecialchars($article['titre']) ?>" class="img-limite">
        <?php endif; ?>
        <p>Catégorie : <?= htmlspecialchars($article['categorie_nom'] ?? 'Non catégorisé') ?></p>
        <p>Publié par <?= htmlspecialchars($article['utilisateur_nom']) . ' ' . htmlspecialchars($article['utilisateur_prenom']) ?> le <?= htmlspecialchars($article['date_publication']) ?></p>
        
        <h2>Commentaires</h2>
        <?php if (!empty($commentaires)): ?>
            <?php foreach ($commentaires as $commentaire): ?>
                <div class="commentaire">
                    <p><?= htmlspecialchars($commentaire['contenu']) ?></p>
                    <p><small>Posté le <?= htmlspecialchars($commentaire['date_commentaire']) ?></small></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun commentaire pour cet article.</p>
        <?php endif; ?>

        <h3>Laisser un commentaire</h3>
        <?php if (isset($_SESSION['id_utilisateur'])): ?>
            <form action="inc/ajouter_commentaire.php" method="POST">
                <input type="hidden" name="id_article" value="<?= $id_article ?>">
                <label for="contenu">Commentaire :</label><br>
                <textarea id="contenu" name="contenu" rows="4" required></textarea><br><br>
                <button type="submit">Envoyer</button>
            </form>
        <?php else: ?>
            <p>Vous devez être <a href="connexion.php">connecté</a> pour laisser un commentaire.</p>
        <?php endif; ?>
    </div>
    <?php include 'inc/footer.php'; ?>
</body>
</html>
