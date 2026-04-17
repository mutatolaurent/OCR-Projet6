<?php

/**
 * Contrôleur pour les accès aux pages concernant les propriétaires de livres.
 */
class UserController
{
    /**
     * Affiche la page d'informations publiques sur un propriétaire de livres
     * @return void
     */
    public function showOwnerPublicInfo(): void
    {

        // Si on ne récupère pas un ID de livre valide, on redirige vers la HP
        $idowner = Utils::request('id', null);
        if (filter_var($idowner, FILTER_VALIDATE_INT) === false) {
            Utils::redirect("home");
        }

        // On récupère les infos sur le propriétaire y compris les livres qui luis sont rattachés
        $userManager = new UserManager();
        $filterOnBookState = 1;
        $user = $userManager->getUserById($idowner, $filterOnBookState);

        // Si aucun propriétaire trouvé ALORS on redirige vers la HP
        if ($user === false) {
            Utils::redirect("home");
        }

        // On affiche la page d'information sur le livre
        $view = new View($user[0]->getPseudo());
        $view->render("ownerInfo", [
            'user' => $user
        ]);
    }

    /**
     * Affichage des informations du compte utilisateur
     * @return void
     */
    public function showMyAccount(): void
    {

        // On vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }

        // On récupère les données et erreurs puis on vide la session immédiatement
        $formData['error'] = $_SESSION['error'] ?? [];
        $formData['credential'] = $_SESSION['credential'] ?? ['email' => '', 'password' => '', 'pseudo' => ''];
        $formData['updated'] = $_SESSION['updated'] ?? false;

        // On nettoie la session pour que les messages ne restent pas au prochain rafraîchissement
        unset($_SESSION['error'], $_SESSION['credential'], $_SESSION['updated']);


        // On récupère toutes les informations sur ce User
        // On récupère les infos sur le propriétaire y compris les livres qui luis sont rattachés
        $userManager = new UserManager();
        $user = $userManager->getUserById($_SESSION['user']->getId());

        // Si aucun propriétaire trouvé ALORS on déconnecte et on redirige vers la HP
        if ($user === false) {
            Utils::redirect("disconnectUser");
        }

        $user[] = $formData;

        //
        if (Utils::request('zoom', null) == 'viewAvatar') {

            // On affiche la page d'information sur le livre
            $view = new View("Avatar ".$user[0]->getPseudo());
            $view->render("myAvatar", [
                'user' => $user
            ]);

        } else {

            // On affiche la page d'information sur le livre
            $view = new View("Compte ".$user[0]->getPseudo());
            $view->render("myAccount", [
                'user' => $user
            ]);
        }
    }

    /**
     * Modification des informations du compte d'un utilisateur
     * @return void
     */
    public function updateMyAccount(): void
    {

        // On vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }

        // On récupère les données saisies dans le formulaire.
        $credential['pseudo'] = htmlspecialchars(Utils::request("pseudo"));
        $credential['email'] = htmlspecialchars(Utils::request("email"));
        $credential['password'] = htmlspecialchars(Utils::request("password"));

        // On récupère en Session les données du compte de l'utilisateur et on repère les infos qui ont changé
        $user = $_SESSION['user'];
        ($user->getEmail() != $credential['email']) ? $emailHasChanged = true : $emailHasChanged = false;
        (!empty($credential['password'])) ? $passwordHasChanged = true : $passwordHasChanged = false;
        ($user->getPseudo() != $credential['pseudo']) ? $pseudoHasChanged = true : $pseudoHasChanged = false;

        // RAZ du tableau des erreurs
        $error = [];
        $hasError = false;

        $userManager = new UserManager();

        // --- LOGIQUE DE VALIDATION ---

        // Le champ pseudo doit obligatoirement être renseigné et avoir au moins 3 caratères
        if (mb_strlen($credential['pseudo']) < PSEUDO_MIN_LENGTH) {
            $error['pseudo'] = "! Le pseudo doit contenir au moins ".PSEUDO_MIN_LENGTH." caractères";
            $hasError = true;
        }

        // Si le pseudo a été changé, il ne doit pas être déjà utilisé
        if (!$hasError && $pseudoHasChanged && $userManager->getUserByPseudo($credential['pseudo']) !== null) {
            $error['pseudo'] = "! Ce pseudo est déjà utilisé";
            $hasError = true;
        }

        // Le champ email doit obligatoirement être renseigné
        if (!$hasError && empty($credential['email'])) {
            $error['email'] = "! L'email est obligatoire.";
            $hasError = true;
        }

        // Le champ email doit respecter le pattern d'un email
        if (!$hasError && !filter_var(trim($credential['email']), FILTER_VALIDATE_EMAIL)) {
            $error['email'] = "! L'email n'est pas correct.";
            $hasError = true;
        }

        // L'email ne doit pas être déjà utilisé
        if (!$hasError && $emailHasChanged && $userManager->getUserByLogin($credential['email']) !== null) {
            $error['email'] = "! Cet email est déjà utilisé";
            $hasError = true;
        }

        // Si le champ password a été modifié, il doit obligatoirement avoir un minimum de caractères
        if (!$hasError && $passwordHasChanged && mb_strlen($credential['password']) < PASSWORD_MIN_LENGTH) {
            $error['password'] = "! Le mot de passe doit contenir au moins ".PASSWORD_MIN_LENGTH." caractères";
            $hasError = true;
        }

        // --- REDIRECTION SWITCH ---

        // Informations d'inscription non conformes
        if (!empty($error)) {
            // On stocke les messages d'erreurs et les données saisies en session
            // ce qui permettra au formulaire de récupérer le contexte et aisni :
            // . de conserver les données saisie par l'utilisateur de façon à ce qu'il n'ait pas à les re-saisir
            // . de désigner les champs en erreur et les causes d'erreur.
            $_SESSION['error'] = $error;
            $_SESSION['credential'] = $credential;

            // On revient au formulaire de connexion.
            Utils::redirect("myAccount");

            // Update du compte du nouvel utilisateur
        } else {

            if (!$emailHasChanged) {
                $credential['email'] = $user->getEmail();
            }
            if (!$passwordHasChanged) {
                $credential['password'] = $user->getPassword();
            } else {
                $credential['password'] = password_hash($credential['password'], PASSWORD_DEFAULT);
            }
            if (!$pseudoHasChanged) {
                $credential['pseudo'] = $user->getPseudo();
            }
            $credential['idUser'] = $user->getId();

            // $userManager = new UserManager();
            $userChanged = $userManager->updateUser($credential);
            if ($userChanged) {
                $_SESSION['user'] = $userChanged;
                $_SESSION['idUser'] = $userChanged->getId();
                $_SESSION['updated'] = true;
                Utils::redirect("myAccount");
            } else {
                throw new Exception("Echec modification du compte utilisateur.");
            }
        }
    }


    /**
     * Modification des informations du compte d'un utilisateur
     * @return void
     */
    public function updateMyAvatar(): void
    {

        // On vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }

        //
        $user = $_SESSION['user'];

        // RAZ du tableau des erreurs
        $error = [];
        $hasError = false;


        if (!empty($_FILES['avatar']['name'])) {

            // Taille max 1 Mo
            $maxSize = 2 * 1024 * 1024;

            if ($_FILES['avatar']['size'] > $maxSize) {
                $error['avatar'] = "L’image ne doit pas dépasser 1 Mo.";
                $hasError = true;
            }

            // Types autorisés
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!$hasError && !in_array($_FILES['avatar']['type'], $allowedTypes)) {
                $error['avatar'] = "Format d’image non autorisé (jpg, png, webp).";
                $hasError = true;
            }

            // Si pas d’erreur → on enregistre
            $uploadDir = 'images/users/';
            $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);

            // Nom unique basé sur l'utilisateur
            $fileName = 'user_' . $user->getId() . '.' . $extension;

            $destination = $uploadDir . $fileName;

            if (!$hasError && !move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {
                $error['avatar'] = "Erreur lors de l’upload de l’image.";
                $hasError = true;
            }

            // Mise à jour en BD
            $userManager = new UserManager();
            $userManager->updateAvatar($user->getId(), $destination);
            // if (!$userChanged) {
            //     $error['avatar'] = "Erreur lors de la mise à jour en BD.";
            //     $hasError = true;
            // } else {
            //     $_SESSION['user'] = $userChanged;
            // }

            // if (!empty($error)) {
            if ($hasError) {
                // On stocke les messages d'erreurs et les données saisies en session
                // ce qui permettra au formulaire de récupérer le contexte et aisni :
                // . de conserver les données saisie par l'utilisateur de façon à ce qu'il n'ait pas à les re-saisir
                // . de désigner les champs en erreur et les causes d'erreur.
                $_SESSION['error'] = $error;
            }
        }

        // Retour à la page de gestion de l'avatar
        Utils::redirect("myAccount", ['zoom' => 'viewAvatar']);

    }
}
