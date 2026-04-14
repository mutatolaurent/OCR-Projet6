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
        $view = new View("Inscription");
        $view->render("registerForm");
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

    /**TODO
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

        // --- LOGIQUE DE VALIDATION ---

        // Le champ email doit obligatoirement être renseigné
        if (empty($credential['email'])) {
            $error['email'] = "! L'email est obligatoire.";

        } else {

            // Le champ email doit respecter le pattern d'un email
            $credential['email'] = trim($credential['email']);
            if (!filter_var($credential['email'], FILTER_VALIDATE_EMAIL)) {
                $error['email'] = "! L'email n'est pas correct.";

            } else {

                // Le champ password est obligatoire
                if (empty($credential['password'])) {
                    $error['password'] = "! Le mot de passe est obligatoire.";

                } else {

                    // On vérifie que l'utilisateur existe.
                    $userManager = new UserManager();
                    $user = $userManager->getUserByLogin($credential['email']);
                    if (!$user) {
                        $error['auth'] = "! Hum… Mot de passe ou email incorrect";

                    } else {

                        // On vérifie que le mot de passe est correct.
                        if (!password_verify($credential['password'], $user->getPassword())) {
                            // $hash = password_hash($password, PASSWORD_DEFAULT);
                            // throw new Exception("Le mot de passe est incorrect : $hash");
                            $error['auth'] = "! Hum… Mot de passe ou email incorrect";
                        }
                    }
                }
            }
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
}
