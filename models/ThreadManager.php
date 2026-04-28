<?php

/**
 * Classe ThreadManager pour gérer les requêtes liées aux threads et messages.
 */
class ThreadManager extends AbstractEntityManager
{
    /**
     * Récupère toutes les conversations avec un utilisateur et pour chaque conversation
     * récupéère des infos sur le contact et sur le dernier message échangé.
     * @param int $id
     * @param int $idOther
     * @return ?array
     */
    public function getAllThreadByUserId(int $id, User|null $chatContact): ?array
    {

        $threads = [];
        $threadsContext = [];
        $messagesContext = [];
        $threadId = null;

        // On commence par récupérer toutes les conversations de l'utilisateur
        $threads = $this->getThreadsByUserId($id);
        if ((empty($threads) || $threads === null) && $chatContact === null) {
            return null;
        }

        // Pour chaque conversation on récupère les informations sur le contact et sur le dernier message échangé
        // On repère également la conversation active pour l'afficher différemment dans la vue
        while ($thread = array_shift($threads)) {

            // Récupération des informations sur le contact
            $idContact = $thread->getOtherUserId($id);
            $userManager = new UserManager();
            $contact = $userManager->getOnlyUserById($idContact);
            if ($contact === null) {
                continue;
            }

            // On repère le contact de cette conversation si c'est celui de la conversation en cours
            // Ca nous servira a univeau de la vue
            // On récupère également l'ID du thread pour récupérer les messages associés
            $threadActif = false;
            if ($chatContact !== null && $chatContact->getId() == $idContact) {
                $threadActif = true;
                $threadId = $thread->getId();
            }

            // On récupère les informations sur le dernier message échangé
            $lastMessage = $this->getLastMessageByThreadId($thread->getId());

            // Consolidation des résultats de chaque conversation
            $threadsContext[] = [
                'threadActif' => $threadActif,
                'thread' => $thread,
                'contact' => $contact,
                'lastMessage' => $lastMessage
            ];
        }

        // Si un contact est passé en paramètre , on récupère les messages échangés avec ce contact
        if ($chatContact !== null) {
            $messagesContext['chatcontact'] = $chatContact;

            // Si des messages ont déjà été échangés avec ce contact, on les récupère
            if ($threadId !== null) {
                $messages = $this->getMessagesByThreadId($threadId);
                if (!empty($messages)) {
                    $messagesContext['messages'] = $messages;

                    // Si parmi les messages certains n'ont pas encore été lus on considère qu'ils sont lus dorénavant
                    foreach ($messages as $message) {
                        if ($message->getIsRead() == 0) {
                            $this->markMessagesAsRead($threadId, $id);
                        }
                    }
                }
            }
        }

        // Initialisation du tableau des informations à retourner au controlleur
        $chatRoom[] = $threadsContext;
        $chatRoom[] = $messagesContext;

        return $chatRoom;

    }

    /**
     * Envoie un nouveau message dans un thread.
     * @param array $message
     * @return int|null ID du message créé
     */
    public function newMessage(array $message): ?int
    {

        // On recherche l'ID du thread de la conversation entre les 2 utilisateurs
        // Si c'est le premier message de la conversation alors on crée la conversation (le thread)
        $thread = $this->getThreadByUsers($message['idSender'], $message['idContact']);
        if ($thread === null) {
            // Il faut créer le thread entre ces 2 utilisateurs
            $idThread = $this->createThread($message['idSender'], $message['idContact']);
            $thread = $this->getThreadById($idThread);
        }

        // On ajoute le message à la conversation
        return $this->sendMessage($thread->getId(), $message['idSender'], $message['content']);

    }

    /**
     * Récupère un thread par son ID.
     * @param int $id
     * @return ?Thread
     */
    public function getThreadById(int $id): ?Thread
    {
        $sql = "SELECT * FROM thread WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $thread = $result->fetch();
        if ($thread) {
            return new Thread($thread);
        }
        return null;
    }

    /**
     * Récupère un thread entre deux utilisateurs.
     * @param int $userOneId
     * @param int $userTwoId
     * @return ?Thread
     */
    public function getThreadByUsers(int $userOneId, int $userTwoId): ?Thread
    {
        // Appliquer le même tri que createThread pour assurer la cohérence
        if ($userOneId > $userTwoId) {
            [$userOneId, $userTwoId] = [$userTwoId, $userOneId];
        }

        $sql = "SELECT * FROM thread 
                WHERE user_one_id = :userOne AND user_two_id = :userTwo";
        $result = $this->db->query($sql, [
            'userOne' => $userOneId,
            'userTwo' => $userTwoId
        ]);

        $thread = $result->fetch();
        if ($thread) {
            return new Thread($thread);
        }
        return null;
    }

    /**
     * Récupère tous les threads d'un utilisateur.
     * @param int $userId
     * @return array
     */
    public function getThreadsByUserId(int $userId): array
    {
        $sql = "SELECT * FROM thread 
                WHERE user_one_id = :userId OR user_two_id = :userId
                ORDER BY created_at DESC";
        $result = $this->db->query($sql, ['userId' => $userId]);
        $threads = [];
        while ($row = $result->fetch()) {
            $threads[] = new Thread($row);
        }
        return $threads;
    }

    /**
     * Crée un nouveau thread entre deux utilisateurs.
     * @param int $userOneId
     * @param int $userTwoId
     * @return int|null ID du thread créé
     */
    public function createThread(int $userOneId, int $userTwoId): ?int
    {
        // Assurer que userOneId est toujours inférieur à userTwoId pour éviter les doublons
        if ($userOneId > $userTwoId) {
            [$userOneId, $userTwoId] = [$userTwoId, $userOneId];
        }

        // Vérifier si un thread existe déjà entre ces deux utilisateurs
        $existingThread = $this->getThreadByUsers($userOneId, $userTwoId);
        if ($existingThread) {
            return $existingThread->getId();
        }

        $sql = "INSERT INTO thread (user_one_id, user_two_id) VALUES (:userOne, :userTwo)";
        $result = $this->db->query($sql, [
            'userOne' => $userOneId,
            'userTwo' => $userTwoId
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Récupère tous les messages d'un thread.
     * @param int $threadId
     * @return array
     */
    public function getMessagesByThreadId(int $threadId): array
    {
        $sql = "SELECT * FROM message WHERE id_thread = :threadId ORDER BY created_at DESC";
        $result = $this->db->query($sql, ['threadId' => $threadId]);
        $messages = [];
        while ($row = $result->fetch()) {
            $messages[] = new Message($row);
        }
        return $messages;
    }

    /**
     * Récupère le dernier message d'un thread.
     * @param int $threadId
     * @return ?Message
     */
    public function getLastMessageByThreadId(int $threadId): ?Message
    {
        $sql = "SELECT * FROM message WHERE id_thread = :threadId ORDER BY created_at DESC LIMIT 1";
        $result = $this->db->query($sql, ['threadId' => $threadId]);
        $message = $result->fetch();
        if ($message) {
            return new Message($message);
        }
        return null;
    }

    /**
     * Envoie un nouveau message dans un thread.
     * @param int $threadId
     * @param int $senderId
     * @param string $content
     * @return int|null ID du message créé
     */
    public function sendMessage(int $threadId, int $senderId, string $content): ?int
    {
        $sql = "INSERT INTO message (id_thread, id_sender, content) VALUES (:threadId, :senderId, :content)";
        $result = $this->db->query($sql, [
            'threadId' => $threadId,
            'senderId' => $senderId,
            'content' => $content
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Marque tous les messages d'un thread comme lus pour un utilisateur.
     * @param int $threadId
     * @param int $userId
     * @return void
     */
    public function markMessagesAsRead(int $threadId, int $userId): void
    {
        $sql = "UPDATE message 
                SET is_read = 1 
                WHERE id_thread = :threadId 
                AND id_sender != :userId 
                AND is_read = 0";
        $this->db->query($sql, [
            'threadId' => $threadId,
            'userId' => $userId
        ]);
    }

    /**
     * Compte le nombre de messages non lus pour un utilisateur.
     * @param int $userId
     * @return int
     */
    public function countUnreadMessages(int $userId): int
    {
        $sql = "SELECT COUNT(*) as count 
                FROM message m
                JOIN thread t ON m.id_thread = t.id
                WHERE (t.user_one_id = :userId OR t.user_two_id = :userId)
                AND m.id_sender != :userId
                AND m.is_read = 0";
        $result = $this->db->query($sql, ['userId' => $userId]);
        $row = $result->fetch();
        return (int) $row['count'];
    }

    /**
     * Récupère l'ID du user avec lequel l'utilisateur passé en paramètre a échangé son dernier message.
     * @param int $userId : l'ID de l'utilisateur.
     * @return int|null : l'ID du contact, ou null si aucun message.
     */
    public function getLastMessageContactId(int $userId): ?int
    {
        $sql = "SELECT m.id_sender, t.user_one_id, t.user_two_id
                FROM message m
                JOIN thread t ON m.id_thread = t.id
                WHERE t.user_one_id = :userId OR t.user_two_id = :userId
                ORDER BY m.created_at DESC
                LIMIT 1";
        $result = $this->db->query($sql, ['userId' => $userId]);
        $row = $result->fetch();

        if ($row) {
            // Retourner l'ID de l'autre utilisateur (pas celui passé en paramètre)
            return ($row['id_sender'] == $userId)
                ? (($row['user_one_id'] == $userId) ? $row['user_two_id'] : $row['user_one_id'])
                : $row['id_sender'];
        }
        return null;
    }
}
