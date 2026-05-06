<?php

// En fonction des routes utilisées, il est possible d'avoir besoin de la session ; on la démarre dans tous les cas.
session_start();

// Ici on met les constantes utiles,
// les données de connexions à la bdd
// et tout ce qui sert à configurer.

define('TEMPLATE_VIEW_PATH', './views/templates/'); // Le chemin vers les templates de vues.
define('MAIN_VIEW_PATH', TEMPLATE_VIEW_PATH . 'main.php'); // Le chemin vers le template principal.

define('DB_HOST', 'localhost');
define('DB_NAME', 'tomtroc');
define('DB_USER', ''); // A renseigner avec votre nom d'utilisateur de base de données
define('DB_PASS', ''); // A renseigner avec votre mot de passe de base de données

define('PSEUDO_MIN_LENGTH', 3);
define('PASSWORD_MIN_LENGTH', 6);
define('MAX_UPLOAD_BSIZE', 2097152); // Taille maxi autorisée pour l'upload d'un fichier (en bytes)
