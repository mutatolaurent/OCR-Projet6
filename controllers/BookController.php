<?php

/**
 * Contrôleur pour les accès aux pages concernant les livres.
 */
class BookController
{
    /**
     * Affiche la page d'accueil
     * @return void
     */
    public function showHome(): void
    {
        // On vérifie que l'utilisateur est connecté.
        // $this->checkIfUserIsConnected();

        // On récupère les 4 derniers livres partagés.
        $bookManager = new BookManager();
        $books = $bookManager->getAllBooks(4);

        // On affiche la page d'accueil
        $view = new View("Accueil Tom Troc");
        $view->render("home", [
            'books' => $books
        ]);
    }

    /**
     * Affiche la page Nos livres (la bibliothèque)
     * @return void
     */
    public function showLibrary(): void
    {
        // On vérifie que l'utilisateur est connecté.
        // $this->checkIfUserIsConnected();

        // On vérifie si une recherche a été effectuée.
        $searchTerms = Utils::request('search-query', null);

        $options = [];
        $list = [];
        $books = [];

        if ($searchTerms) {
            // Si une recherche a été effectuée, on récupère les livres correspondants.
            $bookManager = new BookManager();
            $list = $bookManager->searchBooks($searchTerms);
            $options = [
                'resultcount' => ($list) ? count($list) : 0,
                'searchterms' => $searchTerms
                ];
        } else {
            // Sinon, on récupère tous les livres.
            $bookManager = new BookManager();
            $list = $bookManager->getAllBooks();
        }
        $books['list'] = $list;
        $books['options'] = $options;

        // On affiche la page avec tous les livres de bibliothèque
        $view = new View("Nos livres");
        $view->render("library", [
            'books' => $books
        ], $options);
    }

    /**
     * Affiche la page d'information sur un livre
     * @return void
     */
    public function showSingleBook(): void
    {
        // On vérifie que l'utilisateur est connecté.
        // $this->checkIfUserIsConnected();

        // Si on ne récupère pas un ID de livre valide, on redirige vers la HP
        $idbook = Utils::request('id', null);
        if (filter_var($idbook, FILTER_VALIDATE_INT) === false) {
            Utils::redirect("home");
        }

        // On récupère les infos du livre.
        $bookManager = new BookManager();
        $books = $bookManager->getBookById($idbook);

        // Si aucun livre trouvé ALORS on redirige vers la HP
        if ($books === false) {
            Utils::redirect("home");
        }

        // On affiche la page d'information sur le livre
        $view = new View($books[0]->getTitle());
        $view->render("singlebook", [
            'books' => $books
        ]);
    }

    /**
     * Affiche le formulaire de modification des informations sur un livre
     * @return void
     */
    public function showBookForUpdate(): void
    {
        // On vérifie que l'utilisateur est connecté.
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }

        // Si on ne récupère pas un ID de livre valide, on redirige vers la HP
        $idbook = Utils::request('id', null);
        if (filter_var($idbook, FILTER_VALIDATE_INT) === false) {
            Utils::redirect("home");
        }

        // On récupère les infos du livre.
        $bookManager = new BookManager();
        $books = $bookManager->getBookById($idbook);
        $book = $books[0];

        // Si aucun livre trouvé ALORS on redirige vers la HP
        if ($book === false) {
            Utils::redirect("home");
        }

        // On affiche la page d'information sur le livre
        $view = new View("Modifications ".$book->getTitle());
        $view->render("myBook", [
            'book' => $book
        ]);
    }

}
