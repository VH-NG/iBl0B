<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    // Vérifiez que l'utilisateur est connecté
    if (!isset($_SESSION['id_utilisateur'])) {
        header('Location: ../connexion.php');
        exit();
    }

    $id_utilisateur = $_SESSION['id_utilisateur'];

    try {
        // Commencez une transaction
        $pdo->beginTransaction();

        // 1. Supprimer les commentaires de l'utilisateur
        $sql = "DELETE FROM commentaires WHERE id_utilisateur = :id_utilisateur";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_utilisateur' => $id_utilisateur]);

        // 2. Trouver tous les articles de l'utilisateur
        $sql = "SELECT id, image FROM articles WHERE id_utilisateur = :id_utilisateur";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_utilisateur' => $id_utilisateur]);
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($articles as $article) {
            $id_article = $article['id'];
            $image_path = '../images/' . $article['image']; // Mettre à jour le chemin si nécessaire

            // 3. Supprimer les commentaires des articles de l'utilisateur
            $sql = "DELETE FROM commentaires WHERE id_article = :id_article";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_article' => $id_article]);

            // 4. Supprimer les entrées de la table article_categorie
            $sql = "DELETE FROM article_categorie WHERE id_article = :id_article";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_article' => $id_article]);

            // 5. Supprimer les articles de l'utilisateur
            $sql = "DELETE FROM articles WHERE id = :id_article";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_article' => $id_article]);

            // 6. Supprimer le fichier d'image associé (s'il existe)
            if (file_exists($image_path) && is_file($image_path)) {
                unlink($image_path);
            }
        }

        // 7. Supprimer l'utilisateur
        $sql = "DELETE FROM utilisateurs WHERE id = :id_utilisateur";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_utilisateur' => $id_utilisateur]);

        // Committer la transaction
        $pdo->commit();

        // Détruire la session et rediriger l'utilisateur
        session_destroy();
        header('Location: ../index.php');
        exit();

    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        $pdo->rollBack();
        echo "Erreur lors de la suppression du compte utilisateur : " . $e->getMessage();
    }
} else {
    header('Location: ../profil.php');
    exit();
}
?>
