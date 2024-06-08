<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_article = $_POST['id_article'];
    $contenu = $_POST['contenu'];
    $id_utilisateur = $_SESSION['id_utilisateur'];

    // Insérez le commentaire dans la base de données
    $sql = "INSERT INTO commentaires (contenu, id_article, id_utilisateur) VALUES (:contenu, :id_article, :id_utilisateur)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'contenu' => $contenu,
        'id_article' => $id_article,
        'id_utilisateur' => $id_utilisateur
    ]);

    header('Location: ../article.php?id=' . $id_article);
    exit();
}
?>
