<?php

/**
 * Ce fichier est le template principal qui "contient" ce qui aura été généré par les autres vues.
 *
 * Les variables qui doivent impérativement être définie sont :
 *      $title string : le titre de la page.
 *      $content string : le contenu de la page.
 */

$action = Utils::request('action', 'home');
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
            <a href="index.php?action=home" class="logo-block">
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

                <!-- Point de menu Accueil-->
                <?php if ($action === "home") { ?>
                    <span class="nav-link active">
                        <span data-text="Accueil">Accueil</span>
                    </span>
                <?php } else { ?>
                    <a href="index.php?action=home" class="nav-link">
                        <span data-text="Accueil">Accueil</span>
                    </a>
                <?php } ?>


                <!-- Point de menu Nos livres -->
                <?php if ($action === "library") { ?>
                    <span class="nav-link active">
                        <span data-text="Nos livres à l'échange">Nos livres à l'échange</span>
                    </span>

                <?php } else { ?>
                    <a href="index.php?action=library" class="nav-link">
                        <span data-text="Nos livres à l'échange">Nos livres à l'échange</span>
                    </a>
                <?php } ?>                

                <!-- Point de menu Messagerie -->                
                <a href="<?= (isset($_SESSION['user'])) ? 'index.php?action=message' : 'index.php?action=connectionForm' ?>" class="nav-link complex-link">
                    <img
                        src="images/icones/messagerie.svg"
                        alt=""
                        class="messagerie-icon"
                    />
                    <span class="text-wrapper" data-text="Messagerie">Messagerie</span>
                    <div class="msg-counter">3</div>
                </a>

                <!-- Point de menu Mon compte -->                
                <a href="<?= (isset($_SESSION['user'])) ? 'index.php?action=myAccount' : 'index.php?action=connectionForm' ?>" class="nav-link complex-link">
                    <img
                        src="images/icones/mon-compte.svg"
                        alt=""
                        class="compte-icon"
                    />
                    <span class="text-wrapper" data-text="Mon compte">Mon compte</span>
                </a>

                <!-- Point de menu Connexion -->                
                <a href="<?= (isset($_SESSION['user'])) ? 'index.php?action=disconnectUser' : 'index.php?action=connectionForm' ?>" class="nav-link">
                    <span data-text="Connexion"><?= (isset($_SESSION['user'])) ? 'Déconnexion' : 'Connexion' ?></span>
                </a>
            </nav>
        </header>

        <main>
            <?= $content /* Ici est affiché le contenu réel de la page. */ ?>
        </main>
        <footer class="main-footer">
            <a href="#">Politique de confidentialité</a>
            <a href="#">Mentions légales</a>
            <a href="index.php?action=home">Tom Troc©</a>
            <a href="index.php?action=home">
                <img src="images/logo/logoTT-vert.svg" alt="Logo Tom Troc vert"/>
            </a>
        </footer>
    </div>
</body>
</html>
