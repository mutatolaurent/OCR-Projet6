<?php

/**
 * Contrôleur pour la gestion des messages entre utilisateurs.
 */
class ThreadController
{
    /**
     * Affiche la chat room de l'utilisateur
     */
    public function showMyChatRoom()
    {

        // On vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }
        $user = $_SESSION['user'];

        // Si un contact est passé en paramètre, on s'assure qu'il existe bien
        $contact = null;
        $idContact = Utils::request('idContact', null);
        if ($idContact !== null) {
            $userManager = new UserManager();
            $contact = $userManager->getOnlyUserById($idContact);
            if ($contact === null) {
                throw new Exception("Tentative de conversation avec un membre inexistant.");
            }
        } else {
            // On récupère le contact du dernier message échangé
            $threadManager = new ThreadManager();
            $idContact = $threadManager->getLastMessageContactId($user->getId());
            if ($idContact !== null) {
                $userManager = new UserManager();
                $contact = $userManager->getOnlyUserById($idContact);
            }
        }

        // On Récupère toutes les conversations avec un utilisateur et pour chaque conversation
        // récupéère des infos sur le contact et sur le dernier message échangé.
        $threadManager = new ThreadManager();
        $chatRoomData = $threadManager->getAllThreadByUserId($user->getId(), $contact);

        // On affiche la page sur la messagerie de l'utilisateur
        $view = new View("Messagerie");
        $view->render("myChatRoom", [
            'chatroom' => $chatRoomData
        ]);
    }

    /**
     * Envoie un message à un autre membre du site
     */
    public function newMessage(): void
    {
        // On vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }
        $user = $_SESSION['user'];

        // On récupère les paramètres
        $message['idContact'] = htmlspecialchars(Utils::request("idContact", null));
        $message['content'] = htmlspecialchars(Utils::request("content", null));

        // Si le message est vide on ne fait rien
        if ($message['content'] === null) {
            return;
        }

        // On vérifie que le contact existe bien
        $contact = null;
        if ($message['idContact'] !== null) {
            $userManager = new UserManager();
            $contact = $userManager->getOnlyUserById($message['idContact']);
            if ($contact === null) {
                throw new Exception("Tentative de conversation avec un membre inexistant.");
            }
        }

        // On crée le nouveau message dans la conversation avec ce contact
        $message['idSender'] = $user->getId();
        $threadManager = new ThreadManager();
        $result = $threadManager->newMessage($message);

        // Retour à la page de la chat room avec prise en compte du nouveau message
        Utils::redirect("showMyChatRoom", ['idContact' => $message['idContact']]);

    }

    /**
     * Affiche un JSON avec le nombre de messages non lus pour l'utilisateur connecté.
     *
     */
    public function getUnreadMessage(): void
    {

        // On vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            echo json_encode(['count' => 0]);
            exit;
        }
        $user = $_SESSION['user'];

        // On recherche le nombre de messages non lus pour cet utilisateur
        $threadManager = new ThreadManager();
        $count = $threadManager->countUnreadMessages($user->getId());

        // On retourne le nombre au format JSON pour qu'il soit exploité par
        // le script javascript chargé d'afficher la pastille à côté du point de menu Messagerie
        echo json_encode(['count' => (int)$count]);
    }

}
