<?php

require_once 'config/config.php';
require_once 'config/autoload.php';

// On récupère l'action demandée par l'utilisateur.
// Si aucune action n'est demandée, on affiche la page d'accueil.
$action = Utils::request('action', 'home');

// Try catch global pour gérer les erreurs
try {
    // Pour chaque action, on appelle le bon contrôleur et la bonne méthode.
    switch ($action) {
        // Pages accessibles à tous.
        case 'home':
            $bookController = new BookController();
            $bookController->showHome();
            break;

        case 'library':
            $bookController = new BookController();
            $bookController->showLibrary();
            break;

        case 'book':
            $bookController = new BookController();
            $bookController->showSingleBook();
            break;

        case 'owner':
            $userController = new UserController();
            $userController->showOwnerPublicInfo();
            break;

        case 'connectionForm':
            $authController = new AuthController();
            $authController->showConnectionForm();
            break;

        case 'connectUser':
            $authController = new AuthController();
            $authController->connectUser();
            break;

        case 'disconnectUser':
            $authController = new AuthController();
            $authController->disconnectUser();
            break;

        case 'registerForm':
            $authController = new AuthController();
            $authController->showRegisterForm();
            break;

        case 'registerUser':
            $authController = new AuthController();
            $authController->registerUser();
            break;

        case 'myAccount':
            $userController = new UserController();
            $userController->showMyAccount();
            break;

            // case 'connexion':
            //     $userController = new UserController();
            //     $userController->showConnexionForm();
            //     break;
            // case 'inscription':
            //     $userController = new UserController();
            //     $userController->showInscriptionForm();
            //     break;
            // case 'message':
            //     $messageController = new MessageController();
            //     $messageController->showMessages();
            //     break;
            // case 'moncompte':
            //     $userController = new UserController();
            //     $userController->showMonCompte();
            //     break;


        default:
            throw new Exception("La page demandée n'existe pas.");
    }
} catch (Exception $e) {
    // En cas d'erreur, on affiche la page d'erreur.
    $errorView = new View('Erreur');
    $errorView->render('errorPage', ['errorMessage' => $e->getMessage()]);
}
