<?php
session_start();
require 'inc/database.php';

// Vérifiez que l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: connexion.php');
    exit();
}

// Vérifiez que l'ID de l'article est passé dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID d'article non spécifié.";
    exit();
}

$id_article = $_GET['id'];
$id_utilisateur = $_SESSION['id_utilisateur'];

// Récupérer l'article à modifier
$sql = "SELECT * FROM articles WHERE id = :id AND id_utilisateur = :id_utilisateur";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id_article, 'id_utilisateur' => $id_utilisateur]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    echo "Article non trouvé ou vous n'avez pas les droits nécessaires.";
    exit();
}

// Récupérer les catégories pour les afficher dans le formulaire
$sql = "SELECT id, nom FROM categories";
$stmt = $pdo->query($sql);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<link href="images/favicon.png" rel="icon">
    <title>Modifier l'article</title>
</head>
<body>
    <?php include 'inc/header.php'; ?>
    <div class="container">
        <h1>Modifier l'Article</h1>
        <form method="POST" action="inc/edit_article.php">
            <input type="hidden" name="id_article" value="<?= $id_article ?>">
            <label for="titre">Titre :</label><br>
            <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required><br><br>
            <label for="contenu">Contenu :</label><br>
            <textarea id="contenu" name="contenu" rows="4" required><?= htmlspecialchars($article['contenu']) ?></textarea><br><br>
            <label for="categorie">Catégorie :</label><br>
            <select id="categorie" name="id_categorie" required>
                <option value="">Sélectionnez une catégorie</option>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?= htmlspecialchars($categorie['id']) ?>" <?= $categorie['id'] == $article['id_categorie'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categorie['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>
            <button type="submit">Enregistrer</button>
        </form>
    </div>
    <?php include 'inc/footer.php'; ?>
</body>
</html>
