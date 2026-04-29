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
    <script src="js/checkUnreadMessages.js"></script>

</head>

<body>
    <div class="main-container">
        <header class="site-header" role="banner">
            <a href="index.php?action=home" class="logo-block" aria-label="Tom Troc">
                <div class="logo-square">
                    <img
                        src="images/logo/logoTT.svg"
                        alt="Logo Tom Troc"
                        class="logo-svg"
                        aria-hidden=”true”
                    />
                </div>
                <span class="logo-text">Tom Troc</span>
            </a>

            <input type="checkbox" id="menu-cb" />
            <label for="menu-cb" class="burger-menu">
                <img src="images/icones/icon-menu.svg" alt="Menu" />
            </label>

            <nav class="navbar<?= (!isset($_SESSION['user'])) ? ' user-disconnected' : '' ?>" aria-label="Main navigation" role="navigation">

                <!-- Point de menu Accueil-->
                <?php if ($action === "home") { ?>
                    <span class="nav-link active">
                        <span data-text="Accueil" aria-current="page">Accueil</span>
                    </span>
                <?php } else { ?>
                    <a href="index.php?action=home" class="nav-link" aria-label="Page d'accueil du site">
                        <span data-text="Accueil">Accueil</span>
                    </a>
                <?php } ?>


                <!-- Point de menu Nos livres -->
                <?php if ($action === "library") { ?>
                    <span class="nav-link active">
                        <span data-text="Nos livres à l'échange" aria-current="page">Nos livres à l'échange</span>
                    </span>

                <?php } else { ?>
                    <a href="index.php?action=library" class="nav-link" aria-label="Bibliothèque des livres partagés sur ce site">
                        <span data-text="Nos livres à l'échange">Nos livres à l'échange</span>
                    </a>
                <?php } ?>                

                <!-- Point de menu Messagerie -->                
                <?php if ($action === "showMyChatRoom") { ?>
                    <span class="nav-link complex-link active">
                        <img
                            src="images/icones/messagerie.svg"
                            alt=""
                            class="messagerie-icon"
                            aria-hidden="true"
                        />
                        <span class="text-wrapper" data-text="Messagerie" aria-current="page">Messagerie</span>
                        <div class="msg-counter">3</div>
                    </span>
                <?php } else { ?>
                    <a href="<?= (isset($_SESSION['user'])) ? 'index.php?action=showMyChatRoom' : 'index.php?action=connectionForm' ?>" class="nav-link complex-link" aria-label="Messages échangés avec les autres membres du site">
                        <img
                            src="images/icones/messagerie.svg"
                            alt=""
                            class="messagerie-icon"
                            aria-hidden="true"
                        />
                        <span class="text-wrapper" data-text="Messagerie">Messagerie</span>
                        <div class="msg-counter">3</div>
                    </a>
                <?php } ?> 


                <!-- Point de menu Mon compte -->
                <?php if ($action === "myAccount") { ?>
                    <span class="nav-link complex-link active">
                        <img
                            src="images/icones/mon-compte.svg"
                            alt=""
                            class="compte-icon"
                            aria-hidden="true"
                        />
                        <span class="text-wrapper" data-text="Mon compte" aria-current="page">Mon compte</span>
                    </span>
                <?php } else { ?>
                    <a href="<?= (isset($_SESSION['user'])) ? 'index.php?action=myAccount' : 'index.php?action=connectionForm' ?>" class="nav-link complex-link" aria-label="Les informations de votre compte">
                        <img
                            src="images/icones/mon-compte.svg"
                            alt=""
                            class="compte-icon"
                            aria-hidden="true"
                        />
                        <span class="text-wrapper" data-text="Mon compte">Mon compte</span>
                    </a>
                <?php } ?> 
                

                <!-- Point de menu Connexion -->                
                <a href="<?= (isset($_SESSION['user'])) ? 'index.php?action=disconnectUser' : 'index.php?action=connectionForm' ?>" class="nav-link" aria-label="Connexion / déconnexion à votre compte">
                    <span data-text="Connexion"><?= (isset($_SESSION['user'])) ? 'Déconnexion' : 'Connexion' ?></span>
                </a>
            </nav>
        </header>

        <main>
            <?= $content /* Ici est affiché le contenu réel de la page. */ ?>
        </main>
        <footer>
            <nav class="main-footer" aria-label="footer navigation">
                <a href="#">Politique de confidentialité</a>
                <a href="#">Mentions légales</a>
                <a href="index.php?action=home" aria-label="Page d'accueil du site">Tom Troc©</a>
                <a href="index.php?action=home" aria-label="Page d'accueil du site">
                    <img src="images/logo/logoTT-vert.svg" alt="Logo Tom Troc vert"/>
                </a>
            </nav>
        </footer>
    </div>
</body>
</html>
