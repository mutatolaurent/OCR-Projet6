<?php

/**
 * Classe UserManager pour gérer les requêtes liées aux users et à l'authentification.
 */
class UserManager extends AbstractEntityManager
{
    /**
     * Récupère un user par son login = som email
     * @param string $email
     * @return ?User
     */
    public function getUserByLogin(string $email): ?User
    {
        $sql = "SELECT * FROM user WHERE email = :email";
        $result = $this->db->query($sql, ['email' => $email]);
        $user = $result->fetch();
        if ($user) {
            return new User($user);
        }
        return null;
    }

    /**
     * Récupère un user par son pseudo.
     * @param string $pseudo
     * @return ?User
     */
    public function getUserByPseudo(string $pseudo): ?User
    {
        $sql = "SELECT * FROM user WHERE pseudo = :pseudo";
        $result = $this->db->query($sql, ['pseudo' => $pseudo]);
        $user = $result->fetch();
        if ($user) {
            return new User($user);
        }
        return null;
    }


    /**
     * Récupère un user et ses livres associés, par son ID.
     * @param int $id
     * @param int $excludeStateFilter
     * @return ?User
     */
    public function getUserById(int $id, int $excludeStateFilter = 0): array|bool
    {
        // Une seule requête qui ramène les infos sur l'utilisateur et sur les livres qu'il partage
        $sql = "SELECT u.*, b.id AS book_id, b.title, b.photo as book_photo, b.author, b.description, b.id_state, c.state as stateLabel
            FROM user u 
            LEFT JOIN book b ON u.id = b.id_user
            LEFT JOIN book_state c ON b.id_state = c.id 
            WHERE u.id = :id";
        if ($excludeStateFilter > 0) {
            $sql .= ' and b.id_state <> :stateFilter';
            $result = $this->db->query($sql, ['id' => $id, 'stateFilter' => $excludeStateFilter]);
        } else {
            $result = $this->db->query($sql, ['id' => $id]);
        }

        // Initialisation des variables pour stocker l'utilisateur et ses livres
        $userObject = null;
        $bookObject = null;
        $owner = [];

        // Parcours des résultats pour construire l'objet User et ses livres associés
        while ($user = $result->fetch()) {

            // Au premier passage, on crée l'objet User
            if ($userObject === null) {
                $userData = array_intersect_key(
                    $user,
                    array_flip(['id', 'email', 'pseudo', 'photo', 'created_at'])
                );
                $userObject = new User($userData);
            }

            // Si le livre existe, on crée un objet Book et on l'ajoute à l'utilisateur
            if ($user['book_id'] !== null) {
                $bookObject = new Book([
                'id' => $user['book_id'] ?? '',
                'title' => $user['title'] ?? '[titre absent]',
                'author' => $user['author'] ?? '[titre absent]',
                'photo' => $user['book_photo'] ?? '',
                'description' => $user['description'] ?? '[description absente]',
                'idState' => $user['id_state'] ?? '',
                'stateLabel' => $user['stateLabel'] ?? ''
                ]);

                $userObject->setBooks($bookObject);

            }
        }

        // Si l'utilisateur existe, on le retourne avec ses livres associés
        $owner[] = $userObject;

        return $owner[0] !== null ? $owner : false;

    }

    /**
     * Crée un nouvel utilisateur
     * @param array $credential Informations récupérées du formulaire d'enregistrement
     * @return ?User
     */
    public function createUser(array $credential): ?User
    {

        // Initialisation des informations requises pour créer le compte de l'utilisateur
        $pseudo = $credential['pseudo'];
        $email = $credential['email'];
        $hash = password_hash($credential['password'], PASSWORD_DEFAULT);

        // Requête SQL préparée pour insertion du compte en BD
        $sql = "INSERT INTO user (pseudo, email, password) VALUES (:pseudo, :email, :hash)";

        // Exécution de la requête SQL en lui passant en paramètres les valeurs des champs à insérer en BD
        $this->db->query($sql, ['pseudo' => $pseudo, 'email' => $email, 'hash' => $hash]);

        // On retourne un objet User
        return $this->getUserByLogin($email);

    }

    /**
     * Met à jour les informations de compte d'un utilisateur
     * @param array $credential Informations récupérées du formulaire de modification
     * @return ?User
     */
    public function updateUser(array $credential): ?User
    {

        // Initialisation des informations requises pour créer le compte de l'utilisateur
        $pseudo = $credential['pseudo'];
        $email = $credential['email'];
        $hash = $credential['password'];
        $idUser = $credential['idUser'];

        // Requête SQL préparée pour modification du compte en BD
        $sql = "UPDATE user SET pseudo = :pseudo, email = :email, password = :hash WHERE id = :idUser";

        // Exécution de la requête SQL en lui passant en paramètres les valeurs des champs à insérer en BD
        $this->db->query($sql, ['pseudo' => $pseudo, 'email' => $email, 'hash' => $hash, 'idUser' => $idUser]);

        // On retourne un objet User
        return $this->getUserByLogin($email);
    }

    /**
     * Met à jour la photo de profil utilisateur
     * @param int $idUser Identifiant de l'utilisateur en BD
     * @param string $avatarPathFile Chemin d'accès à la photo
     */
    public function updateAvatar(int $idUser, string $avatarPathFile): void
    {

        // Requête SQL préparée pour modification du compte en BD
        $sql = "UPDATE user SET photo = :photo WHERE id = :idUser";

        // Exécution de la requête SQL en lui passant en paramètres les valeurs des champs à insérer en BD
        $this->db->query($sql, ['photo' => $avatarPathFile, 'idUser' => $idUser]);

        // var_dump($statement);

        // On retourne un objet User
        // return $this->getUserByLogin($email);

    }

    /**
     * Récupère un user par son ID.
     * @param int $id
     * @return ?User
     */
    public function getOnlyUserById(int $id): User|null
    {
        // Une seule requête qui ramène les infos sur l'utilisateur
        $sql = "SELECT * FROM user u WHERE u.id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $user = $result->fetch();

        if ($user) {
            // Une ligne a été trouvée
            return new User($user);
        }
        return null;

    }

}
