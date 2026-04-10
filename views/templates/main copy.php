<?php

/**
 * Ce fichier est le template principal qui "contient" ce qui aura été généré par les autres vues.
 *
 * Les variables qui doivent impérativement être définie sont :
 *      $title string : le titre de la page.
 *      $content string : le contenu de la page.
 */

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?></title>
    <link rel="stylesheet" href="./css/style.css" />
</head>

<body>
    <div class="main-container">
      <header class="site-header">
        <a href="#" class="logo-block">
          <div class="logo-square">
            <img
              src="images/logo/logoTT.svg"
              alt="Logo Tom Troc"
              class="logo-svg"
            />
          </div>
          <span class="logo-text">Tom Troc</span>
        </a>

        <input type="checkbox" id="menu-cb" />
        <label for="menu-cb" class="burger-menu">
          <img src="images/icones/icon-menu.svg" alt="Menu" />
        </label>

        <nav class="navbar">
          <a href="#" class="nav-link"
            ><span data-text="Accueil">Accueil</span></a
          >
          <a href="#" class="nav-link"
            ><span data-text="Nos livres à l'échange"
              >Nos livres à l'échange</span
            ></a
          >

          <a href="#" class="nav-link complex-link">
            <img
              src="images/icones/messagerie.svg"
              alt=""
              class="messagerie-icon"
            />
            <span class="text-wrapper" data-text="Messagerie">Messagerie</span>
            <div class="msg-counter">3</div>
          </a>

          <a href="#" class="nav-link complex-link">
            <img
              src="images/icones/mon-compte.svg"
              alt=""
              class="compte-icon"
            />
            <span class="text-wrapper" data-text="Mon compte">Mon compte</span>
          </a>

          <a href="#" class="nav-link"
            ><span data-text="Connexion">Connexion</span></a
          >
        </nav>
      </header>

    <main>
        <?= $content /* Ici est affiché le contenu réel de la page. */ ?>
    </main>








<body>
    <header>
        <nav>
            <a href="index.php">Articles</a>
            <a href="index.php?action=apropos">À propos</a>
            <?php
            // Si on est connecté, on affiche le menu d'administration, sinon, on affiche le bouton de connexion :
            if (isset($_SESSION['user'])) {
                ?>
                <div class="menu-deroulant">
                    <!-- <span class="menu-titre"><i class="fa-solid fa-bars"></i>Admin</span> -->
                    <!-- Checkbox invisible -->
                    <input type="checkbox" id="toggle-admin" class="menu-toggle">
                    <!-- Le bouton Admin -->
                    <label for="toggle-admin" class="menu-titre">
                        <i class="fa-solid fa-bars icon-open"></i>
                        <i class="fa-solid fa-xmark icon-close"></i>
                        Admin
                    </label>


                    <div class="sous-menu">
                        <a href="index.php?action=admin">Edition des Articles</a>
                        <a href="index.php?action=showMonitorArticleVisits">Monitoring des articles</a>
                        <a href="index.php?action=disconnectUser">Déconnexion</a>
                    </div>
                </div>
                <?php
                // echo '<a href="index.php?action=disconnectUser">Déconnexion</a>';
            } else {
                echo '<a href="index.php?action=connectionForm">Connexion</a>';
            }
?>
        </nav>
        <h1>Emilie Forteroche</h1>
    </header>

    <main>    
        <?= $content /* Ici est affiché le contenu réel de la page. */ ?>
    </main>
    
    <footer>
        <p>Copyright © Emilie Forteroche 2023 - Openclassrooms - <a href="index.php?action=admin">Admin</a>
    </footer>

</body>
</html>