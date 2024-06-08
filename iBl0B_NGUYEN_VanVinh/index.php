<?php
session_start();
require 'inc/database.php';
require 'inc/fonctions.php';

// Initialisation des variables pour les filtres
$categorie_id = isset($_GET['categorie_id']) ? $_GET['categorie_id'] : '';
$search_keyword = isset($_GET['search_keyword']) ? $_GET['search_keyword'] : '';
$order = isset($_GET['order']) ? $_GET['order'] : 'desc'; // Valeur par défaut: du plus récent au plus ancien

// Construire la requête SQL avec les filtres
$sql = "SELECT articles.*, utilisateurs.nom, utilisateurs.prenom FROM articles 
        JOIN utilisateurs ON articles.id_utilisateur = utilisateurs.id 
        LEFT JOIN categories ON articles.id_categorie = categories.id 
        WHERE 1=1";

$params = [];

if (!empty($categorie_id)) {
    $sql .= " AND articles.id_categorie = :categorie_id";
    $params['categorie_id'] = $categorie_id;
}

if (!empty($search_keyword)) {
    $sql .= " AND (articles.titre LIKE :search_keyword OR articles.contenu LIKE :search_keyword)";
    $params['search_keyword'] = '%' . $search_keyword . '%';
}

// Appliquer l'ordre de tri
$sql .= " ORDER BY articles.date_publication " . ($order === 'asc' ? 'ASC' : 'DESC');
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="images/favicon.png" rel="icon">
    <title>Accueil</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'inc/header.php'; ?>
    <div class="container">
        <?php if (!isset($_SESSION['id_utilisateur'])): ?>
            <h1>Bienvenue sur iBl0B</h1>
            <p>Veuillez <a href="inscription.php">vous inscrire</a> ou <a href="connexion.php">vous connecter</a> pour profiter pleinement de notre site.</p>
        <?php else: ?>
            <h1>Bienvenue, <?= htmlspecialchars($_SESSION['prenom']) ?> !</h1>
            <p>Pour commencer, vous pouvez <a href="manage_categories.php">choisir vos catégories préférées</a>.</p>
        <?php endif; ?>

        <?php foreach ($articles as $article): ?>
            <div class="article">
                <h2><?= htmlspecialchars($article['titre']) ?></h2>
                <p><?= htmlspecialchars($article['contenu']) ?></p>
                <?php if (!empty($article['image'])): ?>
                    <img src="images/<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['titre']) ?>">
                <?php endif; ?>
                <p>Publié par <?= htmlspecialchars($article['nom']) . ' ' . htmlspecialchars($article['prenom']) ?> le <?= htmlspecialchars($article['date_publication']) ?></p>
                <a href="article.php?id=<?= $article['id'] ?>">Lire la suite</a>
            </div>
        <?php endforeach; ?>
    </div>

    <?php include 'inc/footer.php'; ?>
</body>
</html>
