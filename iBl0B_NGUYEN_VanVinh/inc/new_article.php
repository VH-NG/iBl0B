<?php
require 'database.php';
require 'fonctions.php';

// Debugging
var_dump($_POST);
var_dump($_FILES);

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $categorie = $_POST['categorie'];

    // Gérer l'upload de l'image
    $image = $_FILES['image'];
    $image_nom = $image['name'];
    $image_tmp = $image['tmp_name'];
    $chemin_image = '../images/' . $image_nom;

    // Créer le répertoire s'il n'existe pas
    if (!is_dir('../images')) {
        mkdir('../images', 0777, true);
    }

    // Vérifier si l'upload de l'image réussit
    if (move_uploaded_file($image_tmp, $chemin_image)) {
        // Récupérer l'ID de l'utilisateur (ex. à partir de la session)
        $id_utilisateur = 1; // À remplacer par la valeur appropriée

        // Préparer et exécuter la requête SQL pour insérer l'article dans la base de données
        $sql = "INSERT INTO articles (titre, contenu, image, id_utilisateur, id_categorie) VALUES (:titre, :contenu, :image, :id_utilisateur, :id_categorie)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'titre' => $titre,
            'contenu' => $contenu,
            'image' => $chemin_image,
            'id_utilisateur' => $id_utilisateur,
            'id_categorie' => $categorie
        ]);

        // Rediriger vers une page de confirmation ou d'accueil
        header('Location: ../index.php');
        exit();
    } else {
        echo "L'upload de l'image a échoué.";
    }
}
?>
