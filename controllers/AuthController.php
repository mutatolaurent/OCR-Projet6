<?php

/**
 * Contrôleur pour les accès aux pages prvées
 */
class AuthController
{
    /**TODO
     * Affichage du formulaire de connexion.
     * @return void
     */
    public function showConnectionForm(): void
    {
        // On récupère les données et erreurs puis on vide la session immédiatement
        $formData['error'] = $_SESSION['error'] ?? [];
        $formData['credential'] = $_SESSION['credential'] ?? ['email' => '', 'password' => ''];

        // On nettoie la session pour que les messages ne restent pas au prochain rafraîchissement
        unset($_SESSION['error'], $_SESSION['credential']);

        $view = new View("Connexion");
        $view->render("connectionForm", [
            'formData' => $formData
        ]);
    }

    /**TODO
     * Affichage du formulaire d'inscription.
     * @return void
     */
    public function showRegisterForm(): void
    {
        // On récupère les données et erreurs puis on vide la session immédiatement
        $formData['error'] = $_SESSION['error'] ?? [];
        $formData['credential'] = $_SESSION['credential'] ?? ['email' => '', 'password' => ''];

        // On nettoie la session pour que les messages ne restent pas au prochain rafraîchissement
        unset($_SESSION['error'], $_SESSION['credential']);

        $view = new View("Inscription");
        $view->render("registerForm", [
            'formData' => $formData
        ]);
    }

    /**TODO
     * Déconnexion de l'utilisateur.
     * @return void
     */
    public function disconnectUser(): void
    {
        // On déconnecte l'utilisateur.
        unset($_SESSION['user']);

        // On redirige vers la page d'accueil.
        Utils::redirect("home");
    }

    /**
     * Connexion de l'utilisateur.
     * @return void
     */
    public function connectUser(): void
    {
        // On récupère les données du formulaire.
        $credential['email'] = htmlspecialchars(Utils::request("email"));
        $credential['password'] = htmlspecialchars(Utils::request("password"));

        // RAZ du tableau des erreurs
        $error = [];
        $hasError = false;

        // --- LOGIQUE DE VALIDATION ---

        // Le champ email doit obligatoirement être renseigné
        if (empty($credential['email'])) {
            $error['email'] = "! L'email est obligatoire.";
            $hasError = true;
        }

        // Le champ email doit respecter le pattern d'un email
        if (!$hasError && !filter_var(trim($credential['email']), FILTER_VALIDATE_EMAIL)) {
            $error['email'] = "! L'email n'est pas correct.";
            $hasError = true;
        }

        // Le champ password est obligatoire
        if (!$hasError && empty($credential['password'])) {
            $error['password'] = "! Le mot de passe est obligatoire.";
            $hasError = true;
        }

        // On vérifie que l'utilisateur existe.
        if (!$hasError) {
            $userManager = new UserManager();
            $user = $userManager->getUserByLogin($credential['email']);
            if (!$user) {
                $error['auth'] = "! Hum… Mot de passe ou email incorrect";
                $hasError = true;
            }
        }

        // On vérifie que le mot de passe est correct.
        if (!$hasError && !password_verify($credential['password'], $user->getPassword())) {
            // $hash = password_hash($password, PASSWORD_DEFAULT);
            // throw new Exception("Le mot de passe est incorrect : $hash");
            $error['auth'] = "! Hum… Mot de passe ou email incorrect";
            $hasError = true;
        }


        // --- REDIRECTION SWITCH ---

        // Connexion refusée
        if (!empty($error)) {
            // On stocke les messages d'erreurs et les données saisies en session
            // ce qui permettra au formulaire de récupérer le contexte et aisni :
            // . de conserver les données saisie par l'utilisateur de façon à ce qu'il n'ait pas à les re-saisir
            // . de désigner les champs en erreur et les causes d'erreur.
            $_SESSION['error'] = $error;
            $_SESSION['credential'] = $credential;

            // On revient au formulaire de connexion.
            Utils::redirect("connectionForm");

            // Connexion acceptée
        } else {

            // On connecte l'utilisateur.
            $_SESSION['user'] = $user;
            $_SESSION['idUser'] = $user->getId();

            // On affiche la page privée Mon compte
            Utils::redirect("myAccount");
        }
    }

    /**
     * Inscription de l'utilisateur.
     * @return void
     */
    public function registerUser(): void
    {
        // On récupère les données du formulaire.
        $credential['pseudo'] = htmlspecialchars(Utils::request("pseudo"));
        $credential['email'] = htmlspecialchars(Utils::request("email"));
        $credential['password'] = htmlspecialchars(Utils::request("password"));

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

        // Le pseudo ne doit pas être déjà utilisé
        if (!$hasError && $userManager->getUserByPseudo($credential['pseudo']) !== null) {
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
        if (!$hasError && $userManager->getUserByLogin($credential['email']) !== null) {
            $error['email'] = "! Cet email est déjà utilisé";
            $hasError = true;
        }

        // Le champ password est obligatoire et doit avoir un minimum de caractères
        if (!$hasError && mb_strlen($credential['password']) < PASSWORD_MIN_LENGTH) {
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
            Utils::redirect("registerForm");

            // Création du compte du nouvel utilisateur
        } else {

            $userManager = new UserManager();
            $user = $userManager->createUser($credential);

            if ($user) {
                $_SESSION['user'] = $user;
                $_SESSION['idUser'] = $user->getId();
                Utils::redirect("myAccount");
            } else {
                throw new Exception("Echec enregistrement d'un nouvel utilisateur.");
            }
        }
    }
}
