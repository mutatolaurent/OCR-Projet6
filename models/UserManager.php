<?php

/**
 * Classe UserManager pour gérer les requêtes liées aux users et à l'authentification.
 */
class UserManager extends AbstractEntityManager
{
    /**
     * Récupère un user par son login.
     * @param string $login
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
     * Récupère un user et ses livres associés, par son ID.
     * @param int $id
     * @return ?User
     */
    public function getOwnerById(int $id): array|bool
    {
        $sql = "SELECT u.*, b.id AS book_id, b.title, b.photo as book_photo, b.author, b.description 
            FROM user u 
            LEFT JOIN book b ON u.id = b.id_user 
            WHERE u.id = :id";

        $result = $this->db->query($sql, ['id' => $id]);

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
                'description' => $user['description'] ?? '[description absente]'
                ]);

                $userObject->setBooks($bookObject);

            }
        }

        // Si l'utilisateur existe, on le retourne avec ses livres associés
        $owner[] = $userObject;

        return $owner[0] !== null ? $owner : false;

    }
}
