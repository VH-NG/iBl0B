<?php
session_start();
require 'database.php';

// Vérifiez que l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: ../connexion.php');
    exit();
}

$id_utilisateur = $_SESSION['id_utilisateur'];

// Traitement de la suppression
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['id'])) {
    $id_article = $_GET['id'];
    $sql = "DELETE FROM articles WHERE id = :id AND id_utilisateur = :id_utilisateur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id_article, 'id_utilisateur' => $id_utilisateur]);
    header('Location: ../mes_articles.php');
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_article'])) {
    $id_article = $_POST['id_article'];
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $id_categorie = $_POST['id_categorie'];

    $sql = "UPDATE articles SET titre = :titre, contenu = :contenu, id_categorie = :id_categorie WHERE id = :id AND id_utilisateur = :id_utilisateur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'titre' => $titre,
        'contenu' => $contenu,
        'id_categorie' => $id_categorie,
        'id' => $id_article,
        'id_utilisateur' => $id_utilisateur
    ]);

    header('Location: ../mes_articles.php');
    exit();
}
?>
