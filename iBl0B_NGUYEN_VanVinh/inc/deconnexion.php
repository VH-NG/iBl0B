<?php
session_start();

// Déconnexion : destruction de la session
session_destroy();

// Redirige vers la page d'accueil
header("Location: ../index.php");
exit();
?>
