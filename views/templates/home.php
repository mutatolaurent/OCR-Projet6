<?php

/**
 * Template pour afficher la page d'accueil.
 */

?>
<section class="home-sect1">
    <div class="home-sect1-left">
        <h1>Rejoignez nos <br />lecteurs passionnés</h1>
        <p>
            Donnez une nouvelle vie à vos livres en les échangeant avec
            d'autres amoureux de la lecture. Nous croyons en la magie du
            partage de connaissances et d'histoires à travers les livres.
        </p>
        <a href="index.php?action=library" class="btn btn-filled btn-sect1">
            <span>Découvrir</span>
        </a>
    </div>
    <div class="home-sect1-right">
        <img src="images/home/home1.jpg" alt="" />
        <p>Hamza</p>
    </div>
</section>

<section class="home-sect2">
    <h2>Les derniers livres ajoutés</h2>
    <div class="cards-nos-livres cards-home-sect2">

        <?php foreach ($books as $book) { ?>
            <a href="indexphp?action=book&id=<?= $book->getId() ?>">
                <article class="card">
                    <img
                        src="<?= $book->getPhoto() ? $book->getPhoto() : 'images/books/placeholder.png' ?>"
                        alt="Photo associée au livre <?= $book->getTitle() ?> - lien vers la page d'informations sur ce livre"
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
    <a href="index.php?action=library" class="btn btn-filled"
        ><span>Voir tous les livres</span></a
    >
</section>

<section class="home-sect3">
    <h2>Comment ça marche ?</h2>
    <p>
        Échanger des livres avec TomTroc c'est simple et amusant ! Suivez
        ces étapes pour commencer :
    </p>
    <div class="howto-home-sect3">
        <p>Inscrivez-vous gratuitement sur notre plateforme.</p>
        <p>
            Ajoutez les livres que vous souhaitez échanger à votre profil.
        </p>
        <p>Parcourez les livres disponibles chez d'autres membres.</p>
        <p>
            Proposez un échange et discutez avec d'autres passionnés de
            lecture.
        </p>
    </div>
    <a href="index.php?action=library" class="btn btn-empty"
        ><span>Voir tous les livres</span></a
    >
</section>

<div class="home-sect4">
    <!-- <img src="images/home/home2.jpg" alt="" width="1440px" /> -->
</div>

<section class="home-sect5">
    <h2>Nos valeurs</h2>
    <p>
        Chez Tom Troc, nous mettons l'accent sur le partage, la découverte
        et la communauté. Nos valeurs sont ancrées dans notre passion pour
        les livres et notre désir de créer des liens entre les lecteurs.
        Nous croyons en la puissance des histoires pour rassembler les gens
        et inspirer des conversations enrichissantes. <br /><br />Notre
        association a été fondée avec une conviction profonde : chaque livre
        mérite d'être lu et partagé. <br /><br />Nous sommes passionnés par
        la création d'une plateforme conviviale qui permet aux lecteurs de
        se connecter, de partager leurs découvertes littéraires et
        d'échanger des livres qui attendent patiemment sur les étagères.
    </p>
    <div class="signature-home-sect5">
        <span>L'équipe Tom Troc</span>
        <img src="images/icones/signature.svg" alt="" />
    </div>
</section>
