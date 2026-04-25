<?php

/**
 * Contrôleur pour la gestion des messages entre utilisateurs.
 */
class ThreadController
{
    private ThreadManager $threadManager;
    private UserManager $userManager;

    public function __construct()
    {
        $this->threadManager = new ThreadManager();
        $this->userManager = new UserManager();
    }

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
     * Affiche la liste des conversations de l'utilisateur connecté.
     * @return void
     */
    public function showConversations(): void
    {
        $this->checkAuth();

        $userId = $_SESSION['user_id'];
        $threads = $this->threadManager->getThreadsByUserId($userId);

        // Enrichir chaque thread avec les infos de l'autre utilisateur et le dernier message
        $conversations = [];
        foreach ($threads as $thread) {
            $otherUserId = $thread->getOtherUserId($userId);
            $otherUser = $this->userManager->getUserById($otherUserId);
            $lastMessage = $this->threadManager->getLastMessageByThreadId($thread->getId());

            $conversations[] = [
                'thread' => $thread,
                'otherUser' => $otherUser,
                'lastMessage' => $lastMessage
            ];
        }

        $unreadCount = $this->threadManager->countUnreadMessages($userId);

        $view = new View("Mes conversations");
        $view->render("conversations", [
            'conversations' => $conversations,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Affiche une conversation spécifique.
     * @param int $threadId
     * @return void
     */
    public function showConversation(int $threadId): void
    {
        $this->checkAuth();

        $userId = $_SESSION['user_id'];
        $thread = $this->threadManager->getThreadById($threadId);

        if (!$thread) {
            $this->redirectToError("Conversation introuvable.");
            return;
        }

        // Vérifier que l'utilisateur fait partie de la conversation
        if ($thread->getUserOneId() !== $userId && $thread->getUserTwoId() !== $userId) {
            $this->redirectToError("Vous n'avez pas accès à cette conversation.");
            return;
        }

        // Marquer les messages comme lus
        $this->threadManager->markMessagesAsRead($threadId, $userId);

        // Récupérer les messages
        $messages = $this->threadManager->getMessagesByThreadId($threadId);

        // Récupérer les infos de l'autre utilisateur
        $otherUserId = $thread->getOtherUserId($userId);
        $otherUser = $this->userManager->getUserById($otherUserId);

        $unreadCount = $this->threadManager->countUnreadMessages($userId);

        $view = new View("Conversation");
        $view->render("conversation", [
            'thread' => $thread,
            'otherUser' => $otherUser,
            'messages' => $messages,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Démarre ou récupère une conversation avec un utilisateur.
     * @param int $recipientId
     * @return void
     */
    public function startConversation(int $recipientId): void
    {
        $this->checkAuth();

        $senderId = $_SESSION['user_id'];

        // Vérifier que le destinataire existe
        $recipient = $this->userManager->getUserById($recipientId);
        if (!$recipient) {
            $this->redirectToError("Utilisateur introuvable.");
            return;
        }

        // Vérifier qu'on ne s'adresse pas à soi-même
        if ($senderId === $recipientId) {
            $this->redirectToError("Vous ne pouvez pas démarrer une conversation avec vous-même.");
            return;
        }

        // Créer ou récupérer le thread
        $threadId = $this->threadManager->createThread($senderId, $recipientId);

        // Rediriger vers la conversation
        $this->redirectToConversation($threadId);
    }

    /**
     * Envoie un message dans une conversation.
     * @return void
     */
    public function sendMessageBis(): void
    {
        $this->checkAuth();

        $userId = $_SESSION['user_id'];
        $threadId = $_POST['thread_id'] ?? 0;
        $content = $_POST['content'] ?? '';

        // Validation
        if (empty($threadId) || empty($content)) {
            $_SESSION['error'] = "Données invalides.";
            $this->redirectToConversation($threadId);
            return;
        }

        // Vérifier que le thread existe et que l'utilisateur y participe
        $thread = $this->threadManager->getThreadById($threadId);
        if (!$thread) {
            $this->redirectToError("Conversation introuvable.");
            return;
        }

        if ($thread->getUserOneId() !== $userId && $thread->getUserTwoId() !== $userId) {
            $this->redirectToError("Vous n'avez pas accès à cette conversation.");
            return;
        }

        // Envoyer le message
        $this->threadManager->sendMessage($threadId, $userId, $content);

        // Rediriger vers la conversation
        $this->redirectToConversation($threadId);
    }

    /**
     * Supprime une conversation.
     * @param int $threadId
     * @return void
     */
    public function deleteConversation(int $threadId): void
    {
        $this->checkAuth();

        $userId = $_SESSION['user_id'];
        $thread = $this->threadManager->getThreadById($threadId);

        if (!$thread) {
            $this->redirectToError("Conversation introuvable.");
            return;
        }

        // Vérifier que l'utilisateur fait partie de la conversation
        if ($thread->getUserOneId() !== $userId && $thread->getUserTwoId() !== $userId) {
            $this->redirectToError("Vous n'avez pas accès à cette conversation.");
            return;
        }

        // Supprimer le thread (les messages sont supprimés en cascade)
        $this->threadManager->deleteThread($threadId);

        // Rediriger vers la liste des conversations
        header("Location: ?action=conversations");
        exit;
    }

    /**
     * Vérifie que l'utilisateur est connecté.
     * @return void
     */
    private function checkAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?action=connection");
            exit;
        }
    }

    /**
     * Redirige vers une conversation.
     * @param int $threadId
     * @return void
     */
    private function redirectToConversation(int $threadId): void
    {
        header("Location: ?action=conversation&thread_id=" . $threadId);
        exit;
    }

    /**
     * Redirige vers une page d'erreur.
     * @param string $message
     * @return void
     */
    private function redirectToError(string $message): void
    {
        $_SESSION['error'] = [$message];
        header("Location: ?action=error");
        exit;
    }
}
