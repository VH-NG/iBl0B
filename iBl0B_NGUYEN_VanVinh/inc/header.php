<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>iBlob</title>
    <link rel="stylesheet" href="styles.css">
    <script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    </script>
</head>
<body>
<header>
    <h1>iBl0B ( * - * )</h1>
    <nav>
        <div class="burger-menu">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        <div class="burger">
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <?php if (isset($_SESSION['id_utilisateur'])): ?>
                    <li><a href="profil.php">Profil</a></li>
                    <li><a href="inc/deconnexion.php">Déconnexion</a></li>
                    <li><a href="mes_articles.php">Mes articles</a></li>
                    <li><a href="add_article.php">Ajouter un article</a></li>
                <?php else: ?>
                    <li><a href="inscription.php">Inscription</a></li>
                    <li><a href="connexion.php">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="filters">
            <form method="GET" action="index.php">
                <label for="search_keyword">Recherche:</label>
                <input type="text" id="search_keyword" name="search_keyword" value="<?= isset($_GET['search_keyword']) ? htmlspecialchars($_GET['search_keyword']) : '' ?>">
                <label for="order">Trier/date:</label>
                <select id="order" name="order">
                    <option value="desc" <?= isset($_GET['order']) && $_GET['order'] == 'desc' ? 'selected' : '' ?>>Date croissante</option>
                    <option value="asc" <?= isset($_GET['order']) && $_GET['order'] == 'asc' ? 'selected' : '' ?>>Date décroissante</option>
                </select>
                <button type="submit">Filtrer</button>
            </form>
        </div>
    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const burgerMenu = document.querySelector('.burger-menu');
        const burger = document.querySelector('.burger');

        burgerMenu.addEventListener('click', function () {
            burger.classList.toggle('active');
        });
    });
</script>
</body>
</html>
