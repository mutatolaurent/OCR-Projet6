<?php

/**
 * Template pour afficher la page des informations publiques sur un propriétaire de livres.
 */
?>
<section class="cpt-public-sect">
    <div class="cpt-public-left">
        <div class="cpt-img-profil-container">
            <img
            class="cpt-img-profil"
            src="<?= $user[0]->getPhoto() ?>"
            alt="image du profil de <?= $user[0]->getPseudo() ?>"
            />
        </div>

        <h2 class="cpt-owner"><?= $user[0]->getPseudo() ?></h2>
        <p class="cpt-time">Membre depuis <?= Utils::formatDateDiff($user[0]->getCreatedAt()) ?></p>
        <p class="cpt-bib">BIBLIOTHEQUE</p>
        <div class="cpt-count-container">
            <img
            class="cpt-count-img"
            src="images/icones/bibliotheque.svg"
            alt=""
            />
            <p class="cpt-count"><?= count($user[0]->getBooks()) ?> livres</p>
        </div>
        <a href="index.php?action=showMyChatRoom&idContact=<?= $user[0]->getId() ?>" class="btn btn-empty"><span>Ecrire un message</span></a>
    </div>

    <div class="cpt-public-right">
    <table>
        <thead>
        <tr>
            <th>PHOTO</th>
            <th>TITRE</th>
            <th>AUTEUR</th>
            <th>DESCRIPTION</th>
        </tr>
        </thead>
        <tbody>
            <?php if ($user[0]->getBooks() !== false) { ?>     
                <?php foreach ($user[0]->getBooks() as $book) { ?>
                    <tr>
                        <td>            
                            <a href="index.php?action=book&id=<?= $book->getId() ?>">
                                <img
                                src="<?= $book->getPhoto() ?>"
                                alt="Image lien vers la page d'information du livre <?= $book->getTitle() ?> de <?= $book->getAuthor() ?>"
                                />
                            </a>
                        </td>
                        <td><?= $book->getTitle() ?></td>
                        <td><?= $book->getAuthor() ?></td>

                        <td class="description-td"><?= mb_substr($book->getDescription(), 0, 100).'...' ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
    </div>
</section>
