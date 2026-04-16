<?php

/**
 * Template pour afficher la page de modification des informations sur un livre
 *
 */
?>
<section class="modif-infos-main-sect">
    <a href="index.php?action=myAccount" class="return-link">
        <img src="images/icones/retour.svg" alt="Lien retour" />
        <span>retour</span>
    </a>
    <h1>Modifier les informations</h1>

    <div class="modif-book-infos">
    <div class="modif-book-infos-left">
        <p>Photo</p>
        <img
        src="<?= $book->getPhoto() ?>"
        alt="Image de couverture du livre <?= $book->getTitle() ?> de <?= $book->getAuthor() ?>"
        />
        <a href="#" class="edit-link">Modifier la photo</a>
    </div>
    <div class="modif-book-infos-right">
        <form action="index.php?action=updateBook" method="POST">
            <div class="champ-formulaire">
                <label for="title">Titre</label>
                <input
                class="input-error"
                type="text"
                name="title"
                id="title"
                value="<?= $book->getTitle() ?>"
                />
                <span class="text-error">Message en cas d'erreur</span>
            </div>
            <div class="champ-formulaire">
                <label for="author"><?= $book->getAuthor() ?></label>
                <input type="text" name="author" id="author" />
                <span class="text-error">Message en cas d'erreur</span>
            </div>
            <div class="champ-formulaire">
                <label for="description">Commentaire</label>
                <textarea
                    name="description"
                    id="description"
                    ><?= $book->getDescription() ?></textarea>
                <span class="text-error">Message en cas d'erreur</span>
            </div>
            <div class="champ-formulaire">
                <label for="book-state">Disponibilité</label>
                <select name="book-state" id="book-state">
                <option value="disponible">Disponible</option>
                <option value="non-dispo">Non dispo</option>
                </select>
            </div>
            <button class="btn btn-filled" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir enregistrer ces modifications ?") ?>>Valider</button>
        </form>
    </div>
    </div>
</section>
