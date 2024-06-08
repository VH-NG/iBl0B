<?php
function connexion_utilisateur($pdo, $email, $mot_de_passe) {
    $sql = "SELECT * FROM utilisateurs WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
        session_start();
        $_SESSION['id_utilisateur'] = $utilisateur['id'];
        $_SESSION['nom'] = $utilisateur['nom'];
        $_SESSION['prenom'] = $utilisateur['prenom'];
        return true;
    } else {
        return false;
    }
}

function creer_utilisateur($pdo, $nom, $prenom, $email, $mot_de_passe) {
    // Vérifier si l'email existe déjà
    $sql = "SELECT COUNT(*) FROM utilisateurs WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $email_exists = $stmt->fetchColumn();

    if ($email_exists) {
        return 'L\'email est déjà pris.';
    }

    $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES (:nom, :prenom, :email, :mot_de_passe)";
    $stmt = $pdo->prepare($sql);
    $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);
    if ($stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'mot_de_passe' => $mot_de_passe_hash])) {
        return true;
    } else {
        return 'Erreur lors de la création du compte.';
    }
}
?>

