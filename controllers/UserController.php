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
        // On vérifie que l'utilisateur est connecté.
        // $this->checkIfUserIsConnected();

        // Si on ne récupère pas un ID de livre valide, on redirige vers la HP
        $idowner = Utils::request('id', null);
        if (filter_var($idowner, FILTER_VALIDATE_INT) === false) {
            Utils::redirect("home");
        }

        // On récupère les infos du livre.
        $userManager = new UserManager();
        $user = $userManager->getOwnerById($idowner);

        // Si aucun propriétaire trouvé ALORS on redirige vers la HP
        if ($user === false) {
            Utils::redirect("home");
        }

        // On affiche la page d'information sur le livre
        $view = new View($user[0]->getPseudo());
        $view->render("compte-public", [
            'user' => $user
        ]);
    }
}
