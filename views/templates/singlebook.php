<?php

/**
 * Template pour afficher la page d'information sur un livre.
 */
?>
<div class="breadcrump">
    <a href="index.php?action=library">Nos livres à l'échange</a> > <a href="index.php?action=book&id=<?= $books[0]->getId() ?>"><?= $books[0]->getTitle() ?></a>
</div>
<section class="single-livre-container">
    <img
        class="livre-image"
        src="<?= $books[0]->getPhoto() ?>"
        alt="Photo de couverture du livre <?= $books[0]->getTitle() ?> écrit par <?= $books[0]->getAuthor() ?>"
    />
    <div class="livre-info">
        <h1><?= $books[0]->getTitle() ?></h1>
        <h2><?= $books[0]->getAuthor() ?></h2>
        <img
            class="line-separator"
            src="images/icones/separator1.svg"
            alt=""
        />
        <h3>DESCRIPTION</h3>
        <p><?= $books[0]->getDescription() ?></p>
        <h3>PROPRIETAIRE</h3>
        <a href="index.php?action=owner&id=<?= $books[0]->getOwner()->getId() ?>" class="picto-owner">
            <img
                class="img-owner"
                src="<?= $books[0]->getOwner()->getPhoto() ?>"
                alt=""
            />
            <span class="pseudo-owner"><?= $books[0]->getOwner()->getPseudo() ?></span>
        </a>
        <a href="#" class="btn btn-filled">
            <span>Envoyez un message</span>
        </a>
    </div>
</section>
