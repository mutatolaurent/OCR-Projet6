<?php

/**
 * Contrôleur pour les accès aux pages concernant les livres.
 */
class BookController
{
    /**
     * Affiche la page d'administration.
     * @return void
     */
    public function showHome(): void
    {
        // On vérifie que l'utilisateur est connecté.
        // $this->checkIfUserIsConnected();

        // On récupère les 4 derniers livres partagés.
        $bookManager = new BookManager();
        $books = $bookManager->getAllBooks(4);

        // On affiche la page d'administration.
        $view = new View("Accueil Tom Troc");
        $view->render("home", [
            'books' => $books
        ]);
    }

    /**
     * Affiche la page d'administration.
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


        // if ($searchTerms) {
        //     // Si une recherche a été effectuée, on récupère les livres correspondants.
        //     $bookManager = new BookManager();
        //     $books = $bookManager->searchBooks($searchTerms);
        //     $options = [
        //         'resultcount' => ($books) ? count($books) : 0,
        //         'searchterms' => $searchTerms
        //         ];
        // } else {
        //     // Sinon, on récupère tous les livres.
        //     $bookManager = new BookManager();
        //     $books = $bookManager->getAllBooks();
        // }

        // echo "<pre>";
        // var_dump($books);
        // echo "</pre>";

        // On affiche la page d'administration.
        $view = new View("Nos livres");
        $view->render("library", [
            'books' => $books
        ], $options);
    }

}
