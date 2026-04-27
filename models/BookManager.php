<?php

/**
 * Classe qui gère les livres
 */
class BookManager extends AbstractEntityManager
{
    /**
     * Récupère tous les livres de la bibliothèque.
     * @param int|null $nbBooks : le nombre de livres à récupérer, ou null pour tous les livres.
     * @return array : un tableau d'objets Livre composé avec l'objet User du propriétaire du livre.
     */
    public function getAllBooks(?int $nbBooks = null, int $excludeStateFilter = 0): array
    {

        // Préparation de la requête SQL pour récupérer les livres avec les infos du propriétaire
        $sql = "SELECT a.*, b.id as user_id, b.pseudo, b.email, b.photo as profilpict
                FROM book a, user b 
                WHERE a.id_user = b.id";
        if ($excludeStateFilter > 0) {
            $sql .= ' and a.id_state <> '. (int)$excludeStateFilter;
        }
        if ($nbBooks !== null) {
            $sql .= " ORDER BY created_at DESC LIMIT " . (int)$nbBooks;
        } else {
            $sql .= " ORDER BY created_at DESC";
        }

        // Exécution de la requête et construction des objets Book avec les infos du propriétaire
        $result = $this->db->query($sql);

        return $this->buildBooksWithOwner($result);
    }

    /**
     * Récupère un livre par son ID.
     * @param int $id : l'ID du livre à récupérer.
     * @return array|bool : un tableau contenant l'objet Book avec l'objet User du propriétaire, ou false si aucun livre trouvé.
     */
    public function getBookById(int $id): array|bool
    {
        // Préparation de la requête SQL pour récupérer le livre avec les infos du propriétaire
        $sql = "SELECT a.*, b.id as user_id, b.pseudo, b.email, b.photo as profilpict
                FROM book a, user b 
                WHERE a.id = :id AND a.id_user = b.id";

        // Exécution de la requête avec le paramètre ID
        $result = $this->db->query($sql, ['id' => $id]);

        // Construction des objets Book avec les infos du propriétaire
        $books = $this->buildBooksWithOwner($result);

        // Retourne le tableau si un livre est trouvé, sinon false
        return !empty($books) ? $books : false;
    }

    /**
    * Recherche des livres en fonction de termes de recherche.
    * La recherche est effectuée sur les champs title et author.
    * @param string $terms : les termes de recherche
    * @return array|bool : un tableau d'objets Book composé avec l'objet User du propriétaire du livre, ou false si aucun livre trouvé
    */
    public function searchBooks(string $terms): array|bool
    {
        $books = [];

        $terms = implode('* ', explode(' ', $terms)) . '*';

        // 1. Préparation de la requête avec jointure pour l'utilisateur
        // On récupère le score pour pouvoir trier
        $sql = "SELECT a.*, u.id as user_id, u.pseudo, u.email, u.photo as profilpict,
            MATCH(a.title, a.author) AGAINST(:terms) AS score
            FROM book a
            INNER JOIN user u ON a.id_user = u.id
            WHERE MATCH(a.title, a.author) AGAINST(:terms IN BOOLEAN MODE)
            ORDER BY score DESC";

        // $sql = "SELECT a.*, u.id as user_id, u.pseudo, u.email, u.photo as profilpict,
        //     MATCH(a.title, a.author) AGAINST(:terms) AS score
        //     FROM book a
        //     INNER JOIN user u ON a.id_user = u.id
        //     WHERE MATCH(a.title, a.author) AGAINST(:terms IN NATURAL LANGUAGE MODE)
        //     ORDER BY score DESC";

        $result = $this->db->query($sql, ['terms' => $terms]);

        // --- LA LOGIQUE DE RETOUR ---
        // Si le tableau est vide, on retourne false, sinon on retourne le tableau.
        $books = $this->buildBooksWithOwner($result);
        return !empty($books) ? $books : false;
    }

    /**
     * Construit un tableau d'objets Book à partir d'un résultat de requête.
     * Chaque Book est associé à son objet User propriétaire.
     * @param PDOStatement $result : le résultat de la requête SQL
     * @return array : un tableau d'objets Book avec leurs User associés
     */
    private function buildBooksWithOwner($result): array
    {
        $books = [];

        while ($book = $result->fetch()) {

            // Extraction des données spécifiques au livre
            $bookData = array_intersect_key(
                $book,
                array_flip(['id', 'id_user', 'title', 'author', 'description', 'id_state', 'photo', 'created_at'])
            );
            $bookObject = new Book($bookData);

            // Création de l'objet User pour le propriétaire du livre
            $owner = new User([
                'id' => $book['user_id'] ?? '',
                'pseudo' => $book['pseudo'] ?? '',
                'email' => $book['email'] ?? '',
                'photo' => $book['profilpict'] ?? ''
            ]);

            // Association de l'objet User au livre
            $bookObject->setOwner($owner);
            $books[] = $bookObject;
        }

        return $books;
    }

    /**
     * Récupère les états possibles pour les livres.
     * @return array : un tableau des états possibles pour les livres.
     */
    public function getBookStates(): array
    {

        // Préparation de la requête SQL pour récupérer la liste des états des livres
        $sql = "SELECT id, state FROM book_state";

        // Exécution de la requête et construction des objets Book avec les infos du propriétaire
        $result = $this->db->query($sql);

        $bookStates = [];

        while ($bookState = $result->fetch()) {

            $bookStates[] = new BookState($bookState);

        }

        return !empty($bookStates) ? $bookStates : false;
    }


    /**
     * Met à jour les informations sur un livre
     * @param array $credential Informations récupérées du formulaire de modification
     * @param int îd ID du livre en BD
     * @return ?Book
     */
    public function updateBook(array $bookInfo, int $id): void
    {

        // Initialisation des informations requises pour créer le compte de l'utilisateur
        $title = $bookInfo['title'];
        $author = $bookInfo['author'];
        $description = $bookInfo['description'];
        $idstate = $bookInfo['idstate'];
        $photo = $bookInfo['picture'];

        // Requête SQL préparée pour modification du compte en BD
        $sql = "UPDATE book SET 
            title = :title, 
            author = :author, 
            description = :description, 
            photo = :photo, 
            id_state = :idstate
            WHERE id = :idBook";

        // Exécution de la requête SQL en lui passant en paramètres les valeurs des champs à insérer en BD
        $this->db->query(
            $sql,
            ['idBook' => $id,
            'title' => $title,
            'author' => $author,
            'description' => $description,
            'photo' => $photo,
            'idstate' => $idstate]
        );
    }

    /**
     * Supprime un livre de la BD
     * @param array $credential Informations récupérées du formulaire de modification
     * @param int îd ID du livre en BD
     */
    public function deleteBook(int $id): void
    {

        // Requête SQL préparée pour modification du compte en BD
        $sql = "DELETE FROM book WHERE id = :idBook";

        // Exécution de la requête SQL en lui passant en paramètres les valeurs des champs à insérer en BD
        $this->db->query($sql, ['idBook' => $id]);
    }

    /**
     * Met à jour la photo de profil utilisateur
     * @param int $idUser Identifiant de l'utilisateur en BD
     * @param string $avatarPathFile Chemin d'accès à la photo
     */
    public function updatePicture(int $idBook, string $photoPathFile): void
    {

        // Requête SQL préparée pour modification du compte en BD
        $sql = "UPDATE book SET photo = :photo WHERE id = :idBook";

        // Exécution de la requête SQL en lui passant en paramètres les valeurs des champs à insérer en BD
        $this->db->query($sql, ['photo' => $photoPathFile, 'idBook' => $idBook]);

    }

}
