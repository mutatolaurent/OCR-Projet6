<?php

/**
 * Template pour afficher et modifier la photo d'un livre
 *
 */
?>
<section class="modif-infos-main-sect">
    <a href="index.php?action=showBookForUpdate&id=<?= $books[0]->getId() ?>" class="return-link"
        ><img src="images/icones/retour.svg" alt="Lien retour" /><span>
            retour</span
        ></a
    >
    <h1>Modifier la photo associée au livre</h1>

    <div class="modif-book-infos">
        <div class="modif-book-infos-left">
            <p>Photo</p>
            <img
            src="<?= $books[0]->getPhoto() ?>"
            alt="Photo associée au livre <?= $books[0]->getTitle() ?> de <?= $books[0]->getTitle()?>"
            />
        </div>
        <div class="modif-book-infos-right">
            <form action="index.php?action=updateBookPicture" method="POST" enctype="multipart/form-data">
                <div class="champ-formulaire">
                    <label for="avatar">Changer la photo</label>
                    <input
                        type="file"
                        name="picture"
                        id="picture"
                        accept="image/*"
                        class="input-upload"
                    >
                    <span class="info-upload">2 MO maximum. Formats autorisés jpg, webp, png.</span>
                    <?php if (isset($books[1]['error']['picture'])): ?>
                        <span class="text-error"><?= $books[1]['error']['picture'] ?></span>
                    <?php endif; ?>

                </div>
                <button class="btn btn-filled" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir enregistrer ces modifications ?") ?>>Valider</button>
            </form>
        </div>
    </div>
</section>
