<?php

/**
 * Template pour afficher la page d'information sur un livre.
 */
?>
<nav class="breadcrump" aria-label="breadcrumb">
    <a href="index.php?action=library">Nos livres à l'échange</a> > <span aria-current="page"><?= $books[0]->getTitle() ?></span>
</nav>
<section class="single-livre-container">
    <img
        class="livre-image"
        src="<?= $books[0]->getPhoto() ? $books[0]->getPhoto() : 'images/books/placeholder.png' ?>"
        alt="Photo associée au livre <?= $books[0]->getTitle() ?> écrit par <?= $books[0]->getAuthor() ?>"
    />
    <div class="livre-info">
        <h1><?= $books[0]->getTitle() ?></h1>
        <h2><?= $books[0]->getAuthor() ?></h2>
        <img
            class="line-separator"
            src="images/icones/separator1.svg"
            alt=""
            aria-hidden="true"
        />
        <h3>DESCRIPTION</h3>
        <p><?= nl2br($books[0]->getDescription()) ?></p>
        <h3>PROPRIETAIRE</h3>
        <a href="index.php?action=owner&id=<?= $books[0]->getOwner()->getId() ?>" class="picto-owner">
            <img
                class="img-owner"
                src="<?= $books[0]->getOwner()->getPhoto() ?>"
                alt="Avatar du propriétaire"
            />
            <span class="pseudo-owner"><?= $books[0]->getOwner()->getPseudo() ?></span>
        </a>
        <a href="index.php?action=showMyChatRoom&idContact=<?= $books[0]->getOwner()->getId() ?>" class="btn btn-filled" aria-label="Envoyer un message au propriétaire du livre">
            <span>Envoyez un message</span>
        </a>
    </div>
</section>
