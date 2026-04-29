<?php

/**
 * Template pour afficher la page privée de mon compte.
 */
?>
<section class="main-nos-livres">
    <div class="entete-nos-livres">
        <h1>Nos livres</h1>
        <form class="search-form" action="index.php?action=library" method="post" aria-label="Library" role="search">
            <button type="submit" class="search-btn">
                <img src="images/icones/search.svg" alt="Rechercher" />
            </button>
            <input
                class="search-input"
                type="search"
                name="search-query"
                placeholder="Rechercher un livre"
                aria-label="Rechercher un livre.."
            />
        </form>
    </div>
    
    <!-- Affichage des résultats de recherche si une recherche a été effectuée -->
    <?php if (!empty($books['options'])) { ?>
        <p class="search-info">
            <?= $books['options']['resultcount'] ?> résultat(s) trouvé(s) pour la recherche : <strong><?= $books['options']['searchterms'] ?></strong>
        </p>    
    <?php } ?>

    <!-- Affichage de la liste des livres -->
    <div class="cards-nos-livres">
        <?php if ($books['list'] !== false) { ?>     
            <?php foreach ($books['list'] as $book) { ?>
                <a href="indexphp?action=book&id=<?= $book->getId() ?>">
                    <article class="card">
                        <img
                            src="<?= $book->getPhoto() ?>"
                            alt="Photo associée au livre <?= $book->getTitle() ?> de <?= $book->getAuthor() ?> - lien vers la page d'informations sur ce livre"
                        />
                        <h2 class="book-title"><?= $book->getTitle() ?></h2>
                        <p class="book-author"><?= $book->getAuthor() ?></p>    
                        <p class="book-owner">
                            Vendu par : <?= $book->getOwner()->getPseudo() ?>   
                        </p>
                    </article>
                </a>
            <?php } ?>
        <?php } ?>
    </div>
</section>
