<?php

/**
 * Template pour afficher la page de la bibliothèque.
 */
?>
<section class="main-nos-livres">
    <div class="entete-nos-livres">
        <h1>Nos livres</h1>
        <form class="search-form" action="index.php?action=library" method="post">
            <button type="submit" class="search-btn">
                <img src="images/icones/search.svg" alt="Rechercher" />
            </button>
            <input
                class="search-input"
                type="search"
                name="search-query"
                placeholder="Rechercher un livre"
            />
        </form>
    </div>
    
    <div class="cards-nos-livres">
        <?php foreach ($books as $book) { ?>
            <a href="indexphp?action=book&id=<?= $book->getId() ?>">
                <article class="card">
                    <img
                        src="<?= $book->getPhoto() ?>"
                        alt="<?= $book->getTitle() ?>"
                    />
                    <h3 class="book-title"><?= $book->getTitle() ?></h3>
                    <p class="book-author"><?= $book->getAuthor() ?></p>
                    <p class="book-owner">
                        Vendu par : <?= $book->getOwner()->getPseudo() ?>   
                    </p>
                </article>
            </a>
        <?php } ?>
    </div>
</section>
