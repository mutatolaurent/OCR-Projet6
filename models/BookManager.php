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
    public function getAllBooks(?int $nbBooks = null): array
    {

        $books = [];

        // Préparation de la requête SQL pour récupérer les livres avec les infos du propriétaire
        $sql = "select a.*, b.pseudo, b.email 
                from book a, user b 
                where a.id_user = b.id
                order by created_at desc";
        if ($nbBooks !== null) {
            $sql .= " limit " . (int)$nbBooks;
        }

        // Exécution de la requête et construction des objets Book avec les infos du propriétaire
        $result = $this->db->query($sql);


        // Parcours des résultats et création des objets Book avec les infos du propriétaire
        while ($book = $result->fetch()) {

            //
            $bookData = array_intersect_key(
                $book,
                array_flip(['id', 'id_user', 'title', 'author', 'description', 'id_state', 'photo', 'created_at'])
            );
            $bookObject = new Book($bookData);

            // Création de l'objet User pour le propriétaire du livre
            $owner = new User([
                'pseudo' => $book['pseudo'] ?? '',
                'email' => $book['email'] ?? ''
            ]);

            // Association de l'objet User au livre
            $bookObject->setOwner($owner);
            $books[] = $bookObject;
        }

        return $books;
    }

    public function searchBooks(string $terms): array|bool
    {
        $books = [];

        // 1. Préparation de la requête avec jointure pour l'utilisateur
        // On récupère le score pour pouvoir trier
        $sql = "SELECT b.*, u.pseudo,
            MATCH(b.title, b.author) AGAINST(:terms) AS score
            FROM book b
            INNER JOIN user u ON b.id_user = u.id
            WHERE MATCH(b.title, b.author) AGAINST(:terms IN NATURAL LANGUAGE MODE)
            ORDER BY score DESC";

        $result = $this->db->query($sql, ['terms' => $terms]);
        // $result->execute(['terms' => $terms]);

        // Parcours des résultats et création des objets Book avec les infos du propriétaire
        while ($book = $result->fetch()) {

            //
            $bookData = array_intersect_key(
                $book,
                array_flip(['id', 'id_user', 'title', 'author', 'description', 'id_state', 'photo', 'created_at'])
            );
            $bookObject = new Book($bookData);

            // Création de l'objet User pour le propriétaire du livre
            $owner = new User([
                'pseudo' => $book['pseudo'] ?? '',
                'email' => $book['email'] ?? ''
            ]);

            // Association de l'objet User au livre
            $bookObject->setOwner($owner);
            $books[] = $bookObject;
        }


        // while ($data = $stmt->fetch()) {
        //     // 2. Hydratation de l'objet Book
        //     $book = new Book();
        //     $book->setId($data['id']);
        //     $book->setTitle($data['title']);
        //     $book->setAuthor($data['author']);
        //     // ... autres setters ...

        //     // 3. Hydratation de l'objet User (Propriétaire)
        //     $user = new User();
        //     $user->setId($data['idUser']);
        //     $user->setPseudo($data['pseudo']);

        //     $book->setOwner($user);

        //     $books[] = $book;
        // }

        // --- LA LOGIQUE DE RETOUR ---
        // Si le tableau est vide, on retourne false, sinon on retourne le tableau.
        return !empty($books) ? $books : false;
    }
}
