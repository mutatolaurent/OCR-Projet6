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
        $filterOnBookState = 1;
        $books = $bookManager->getAllBooks(4, $filterOnBookState);

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
            $excludeStateFilter = 1;
            $list = $bookManager->getAllBooks(null, $excludeStateFilter);
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

        // On récupère les données et erreurs puis on vide la session immédiatement
        $formData['error'] = $_SESSION['error'] ?? [];
        $formData['bookinfo'] = $_SESSION['bookinfo'] ?? ['title' => '', 'author' => '', 'description' => '', 'book-state' => ''];
        $formData['updated'] = $_SESSION['updated'] ?? false;

        // On nettoie la session pour que les messages ne restent pas au prochain rafraîchissement
        unset($_SESSION['error'], $_SESSION['bookinfo'], $_SESSION['updated']);

        // Si on ne récupère pas un ID de livre valide, on redirige vers la HP
        $idbook = Utils::request('id', null);
        if (filter_var($idbook, FILTER_VALIDATE_INT) === false) {
            Utils::redirect("home");
        }

        // On récupère les infos du livre.
        $bookManager = new BookManager();
        $books = $bookManager->getBookById($idbook);

        // Si aucun livre trouvé ALORS on redirige vers la HP
        if ($books[0] === false) {
            Utils::redirect("home");
        }

        // On stocke l'objet livre en session
        // $_SESSION['idbook'] = $books[0]->getId();
        $_SESSION['book'] = $books[0];

        // On ajoute les données du formulaire au aux données qui seront transmises à la vue
        $books[] = $formData;

        // On récupère les différents états possibles pour un livre
        $bookStates = $bookManager->getBookStates();

        // On ajoute la liste des différents états aux données qui seront transmises à la vue
        $books[] = $bookStates;

        // Sélection de la vue
        if (Utils::request('zoom', null) == 'viewPicture') {

            // On affiche la page de modification de l'image associée au livre
            $view = new View("Modifications ".$books[0]->getTitle());
            $view->render("bookPicture", [
                'books' => $books
            ]);
        } else {
            // On affiche la page d'information sur le livre
            $view = new View("Modifications ".$books[0]->getTitle());
            $view->render("myBook", [
                'books' => $books
            ]);
        }
    }

    /**
     * Modification des informations du compte d'un utilisateur
     * @return void
     */
    public function updateBookInfo(): void
    {

        // On vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }

        // On récupère l'objet Book
        $book = $_SESSION['book'];

        // On récupère les données saisies dans le formulaire.
        $bookInput['title'] = htmlspecialchars(Utils::request("title"));
        $bookInput['author'] = htmlspecialchars(Utils::request("author"));
        $bookInput['description'] = htmlspecialchars(Utils::request("description"));
        $bookInput['idstate'] = htmlspecialchars(Utils::request("idstate"));
        $bookInput['picture'] = null; // L'init se fait plus tard dans le traitement

        // On vérifie s'il y a eu des changements
        $hasChange = false;
        $hasChange = (
            $book->getTitle() !== $bookInput['title'] ||
            $book->getAuthor() !== $bookInput['author'] ||
            $book->getDescription() !== $bookInput['description'] ||
            (int)$book->getIdState() !== (int)$bookInput['idstate'] ||
            !empty($_FILES['picture']['name'])
        );

        // Si aucun changement on réaffiche la page des informations du livre
        if (!$hasChange) {
            Utils::redirect("showBookForUpdate", ['id' => $book->getId()]);
            $_SESSION['updated'] = false;
        }

        // RAZ du tableau des erreurs
        $error = [];
        $hasError = false;

        // --- LOGIQUE DE VALIDATION ---

        // Le champ titre doit obligatoirement être renseigné
        if (empty($bookInput['title'])) {
            $error['title'] = "! Le titre est obligatoire.";
            $hasError = true;
        }

        // Le champ auteur doit obligatoirement être renseigné
        if (empty($bookInput['author'])) {
            $error['author'] = "! L'auteur est obligatoire.";
            $hasError = true;
        }

        // Le champ description doit obligatoirement être renseigné
        if (empty($bookInput['description'])) {
            $error['description'] = "! Un commentaire sur le livre est obligatoire.";
            $hasError = true;
        }

        // Le champ idstate doit obligatoirement être un nombre entier
        if (filter_var($bookInput['idstate'], FILTER_VALIDATE_INT) === false) {
            $error['idstate'] = "! Valeur inconnue.";
            $hasError = true;
        }

        // Si le champ photo a été modifié, on traite l'upload du fichier image sélectionné
        if (!empty($_FILES['picture']['name'])) {
            $result = Utils::uploadFile(
                'picture',                                      // nom du champ
                'images/books/',                                // dossier destination
                MAX_UPLOAD_BSIZE,                               // max MO autorisés
                ['image/jpeg', 'image/png', 'image/webp'],    // types autorisés
                $book->getId()                                 // l'id du livre
            );

            // Si l'upload est OK on récupère le chemin et le nom du nouveau fichier
            if (!$result['success']) {
                $error['picture'] = $result['message'];
                $hasError = true;
            } else {
                $bookInput['picture'] = $result['filename'];
            }
        }


        if ($hasError) {

            // Des erreurs ont été détectée et n'autorise pas les modifications
            // On stocke les messages d'erreurs et les données saisies en session
            // ce qui permettra au formulaire de récupérer le contexte et aisni :
            // . de conserver les données saisie par l'utilisateur de façon à ce qu'il n'ait pas à les re-saisir
            // . de désigner les champs en erreur et les causes d'erreur.
            $_SESSION['error'] = $error;
            $_SESSION['bookinfo'] = $bookInput;

        } else {

            // Si la photo du livre n'a pas changé, on récupère le nom du fichier
            if ($bookInput['picture'] === null) {
                $bookInput['picture'] = $book->getPhoto();
            }

            // Mise à jour des informations sur le livre
            $bookManager = new BookManager();
            $bookManager->updateBook($bookInput, $book->getId());
            $_SESSION['updated'] = true;
        }

        // On réaffiche la page des informations du livre, pour valider les modifications, ou indiquer les champs en erreur
        Utils::redirect("showBookForUpdate", ['id' => $book->getId()]);

    }

    /**
     * Suppression d'un livre en BD
     * @return void
     */
    public function deleteBook(): void
    {
        echo "<H3>bookController->deleteBook</h3>";

        // On vérifie que l'utilisateur est connecté
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

        // Si aucun livre trouvé ALORS on redirige vers la HP
        if ($books === false) {
            Utils::redirect("home");
        }

        // Si l'utilisateur n'est pas le propriétaire du livre on redirige vers la HP
        if ($books[0]->getOwner()->getId() != $_SESSION['user']->getId()) {
            Utils::redirect("home");
        }

        // On supprime l'enregistrement du livre en BD
        $bookManager->deleteBook($idbook);
        Utils::redirect("myAccount");

    }

    /**
     * Modification de la photo associée à un livre
     * @return void
     */
    public function updateBookPicture(): void
    {

        // On vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }

        // On récupère l'objet Book
        $book = $_SESSION['book'];

        // RAZ du tableau des erreurs
        $error = [];
        $hasError = false;

        // Si aucun fichier sélectionné on on retourne au formulaire de modification des informations du livre
        if (empty($_FILES['picture']['name'])) {
            Utils::redirect("showBookForUpdate", ['id' => $book->getId() ]);
        }

        // Appel de la méthode
        $result = Utils::uploadFile(
            'picture',                                      // nom du champ
            'images/books/',                                // dossier destination
            2 * 1024 * 1024,                               // max 2 Mo TODO créer une constante
            ['image/jpeg', 'image/png', 'image/webp'],    // types autorisés
            true,                                           // utiliser l'id comme nom TODO à supprimer au profit du fieldname
            $book->getId()                                 // l'id du livre
        );

        // Traiter le résultat
        if (!$result['success']) {
            $_SESSION['error']['picture'] = $result['message'];
        } else {
            // Mise à jour en BD avec le chemin retourné
            $bookManager = new BookManager();
            $bookManager->updatePicture($book->getId(), $result['filename']);
            $_SESSION['error']['picture'] = $result['message'];

        }

        // Mise à jour en BD
        // if (!$hasError) {

        //     // Mise à jour en BD avec le chemin vers la nouvelle photo de profil
        //     $bookManager = new BookManager();
        //     $bookManager->updatePicture($book->getId(), $destination);
        //     // Retour à la page de gestion de l'image du livre
        //     // Utils::redirect("showBookForUpdate", ['id' => $book->getId() ]);

        //     // TODO : message de feedback

        // } else {

        //     // Init variable de session pour affichage message d'erreur lors de l'affichage du formulaire
        //     $_SESSION['error'] = $error;
        //     // Retour à la page de gestion de l'image du livre
        // }

        // Retour à la page de gestion de l'image du livre
        Utils::redirect("showBookForUpdate", ['zoom' => 'viewPicture', 'id' => $book->getId() ]);

    }

    /**
     * Affiche le formulaire d'ajout d'un nouveau livre
     * @return void
     */
    public function showBookForAdd(): void
    {
        // On vérifie que l'utilisateur est connecté.
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }

        // On récupère les données et erreurs puis on vide la session immédiatement
        $formData['error'] = $_SESSION['error'] ?? [];
        $formData['bookinfo'] = $_SESSION['bookinfo'] ?? ['title' => '', 'author' => '', 'description' => '', 'idstate' => ''];
        $formData['created'] = $_SESSION['created'] ?? false;

        // On nettoie la session pour que les messages ne restent pas au prochain rafraîchissement
        unset($_SESSION['error'], $_SESSION['bookinfo'], $_SESSION['created']);

        // On récupère les différents états possibles pour un livre
        $bookManager = new BookManager();
        $bookStates = $bookManager->getBookStates();

        // On affiche la page du formulaire d'ajout d'un nouveau livre
        $view = new View("Ajouter un livre");
        $view->render("myNewBook", [
            'formData' => $formData,
            'bookStates' => $bookStates
        ]);
    }

    /**
     * Crée un nouveau livre en BD
     * @return void
     */
    public function createBook(): void
    {
        // On vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }

        // On récupère les données saisies dans le formulaire.
        $bookInput['title'] = htmlspecialchars(Utils::request("title"));
        $bookInput['author'] = htmlspecialchars(Utils::request("author"));
        $bookInput['description'] = htmlspecialchars(Utils::request("description"));
        $bookInput['idstate'] = htmlspecialchars(Utils::request("idstate"));
        $bookInput['picture'] = null;

        // RAZ du tableau des erreurs
        $error = [];
        $hasError = false;

        // --- LOGIQUE DE VALIDATION ---

        // Le champ titre doit obligatoirement être renseigné
        if (empty($bookInput['title'])) {
            $error['title'] = "! Le titre est obligatoire.";
            $hasError = true;
        }

        // Le champ auteur doit obligatoirement être renseigné
        if (empty($bookInput['author'])) {
            $error['author'] = "! L'auteur est obligatoire.";
            $hasError = true;
        }

        // Le champ description doit obligatoirement être renseigné
        if (empty($bookInput['description'])) {
            $error['description'] = "! Un commentaire sur le livre est obligatoire.";
            $hasError = true;
        }

        // Le champ idstate doit obligatoirement être un nombre entier
        if (filter_var($bookInput['idstate'], FILTER_VALIDATE_INT) === false) {
            $error['idstate'] = "! Valeur inconnue.";
            $hasError = true;
        }

        // Si le champ photo a été modifié, on traite l'upload du fichier image sélectionné
        if (!empty($_FILES['picture']['name'])) {
            $result = Utils::uploadFile(
                'picture',                                      // nom du champ
                'images/books/',                                // dossier destination
                MAX_UPLOAD_BSIZE,                               // max MO autorisés
                ['image/jpeg', 'image/png', 'image/webp'],    // types autorisés
                false                                           // pas d'id car livre pas encore créé
            );

            // Si l'upload est OK on récupère le chemin et le nom du nouveau fichier
            if (!$result['success']) {
                $error['picture'] = $result['message'];
                $hasError = true;
            } else {
                $bookInput['picture'] = $result['filename'];
            }
        }

        if ($hasError) {
            // Des erreurs ont été détectées
            // On stocke les messages d'erreurs et les données saisies en session
            $_SESSION['error'] = $error;
            $_SESSION['bookinfo'] = $bookInput;

            // On redirige vers le formulaire de création
            Utils::redirect("showBookForAdd");

        } else {
            // Pas d'erreurs, on crée le livre en BD
            $bookManager = new BookManager();
            $userId = $_SESSION['user']->getId();
            $bookId = $bookManager->createBook($bookInput, $userId);
            $_SESSION['created'] = true;

            // On redirige vers le formulaire de modification
            Utils::redirect("showBookForUpdate", ['id' => $bookId]);

        }

    }
}
