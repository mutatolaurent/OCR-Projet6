# site web TomTroc

- Projet réalisé dans le cadre de la formation OpenClassrooms

## Lancez le projet !

### 1) Créez la base de données

Grâce à **phpMyAdmin** ou un autre outil d'administration de MySQL, importez successivement les scripts SQL suivants :

    **db/create_db_tomtroc.sql**

et

    **db/populate_db_tomtroc.sql**

Le premier crée la structure de la base de données, et le second crée un jeu de données qui permet de tester toutes les fonctionnalités du site.

4 utilisateurs sont créés :

. nathalie@gmail.com

. alex@orange.fr

. lise@free.fr

. yves@sfr.fr

Pour chacun d'eux, le mot de passe de connexion est : **password**

### 2) Configurez PHP

Pour la configuration du projet :

1. Renomez le fichier _\_config.php_ (dans le dossier _config_) en _config.php_

2. Editez le et renseignez les paramètres d'accès à votre BD

### 3) Démarrez PHP et accédez au site

J'utilise un serveur PHP intégré, je lance PHP à la racine de mon projet via la commande :

    php -S localhost:8080

et alors le site est accessible à l’adresse :

    http://localhost:8080/index.php

## Informations générales à propos du site

1. Le moteur de recherche disponible sur la page "Nos livres à l'échange" effectue une recherche à la fois sur le titre et l'auteur du livre.

2. Pour les tests d'upload des images, de livres et d'avatars utilisateurs, sont disponibles dans le dossier images/test-upload.

3. Il existe une version responsive du site qui se déclenche pour les écrans de largeur inférieure à 767px.
