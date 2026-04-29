<?php

/**
 * Template pour afficher la page d'ajout d'un nouveau livre
 *
 */
?>
<section class="modif-infos-main-sect">
    <a href="index.php?action=myAccount" class="return-link">
        <img src="images/icones/retour.svg" alt="" aria-hidden="true" />
        <span>retour</span>
    </a>
    <h1>Ajouter un nouveau livre</h1>

    <form action="index.php?action=createBook" method="POST" enctype="multipart/form-data" class="modif-book-infos">

        <div class="modif-book-infos-left">
            <p>Photo</p>
            <img
            src="images/books/placeholder.png"
            id="current-img"
            alt="Image à associer au livre"
            />
            <!-- <label for="picture" class="link-upload" tabindex="0">Ajouter une image</label> -->
            <button 
                type="button" 
                class="link-upload" 
                id="trigger-upload" 
                aria-controls="picture"
                aria-label="Ajouter une image, ouvre le sélecteur de fichiers">
                Ajouter une image
            </button>
            <input
                type="file"
                name="picture"
                id="picture"
                accept="image/*"
                class="input-upload"
                data-max-size="<?= MAX_UPLOAD_BSIZE ?>"
            >
            <?php if (isset($formData['error']['picture'])): ?>
                <span class="text-error"><?= $formData['error']['picture'] ?></span>
            <?php endif; ?>
        </div>
        <div class="modif-book-infos-right">
            <!-- Titre du livre -->
            <div class="champ-formulaire">
                <label for="title">Titre</label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    value="<?= $formData['bookinfo']['title'] ?? '' ?>"
                />
                <?php if (isset($formData['error']['title'])): ?>
                    <span class="text-error"><?= $formData['error']['title'] ?></span>
                <?php endif; ?>
            </div>
            
            <!-- Auteur du livre -->
            <div class="champ-formulaire">
                <label for="author">Auteur</label>
                <input 
                    type="text" 
                    name="author" 
                    id="author" 
                    value="<?= $formData['bookinfo']['author'] ?? '' ?>" 
                />
                <?php if (isset($formData['error']['author'])): ?>
                    <span class="text-error"><?= $formData['error']['author'] ?></span>
                <?php endif; ?>
            </div>

            <!-- Commentaires sur le livre -->
            <div class="champ-formulaire">
                <label for="description">Commentaire</label>
                <textarea
                    name="description"
                    id="description"
                    ><?= $formData['bookinfo']['description'] ?? '' ?></textarea>
                <?php if (isset($formData['error']['description'])): ?>
                    <span class="text-error"><?= $formData['error']['description'] ?></span>
                <?php endif; ?>
            </div>

            <!-- Disponibilité du livre -->
            <div class="champ-formulaire">
                <label for="idstate">Disponibilité</label>
                <select name="idstate" id="idstate">
                <?php foreach ($bookStates as $bookState) { ?>
                    <option 
                        value="<?= $bookState->getId() ?>" 
                        <?= (isset($formData['bookinfo']['idstate']) && $bookState->getId() == $formData['bookinfo']['idstate']) ? ' selected' : '' ?>
                        ><?= $bookState->getState() ?>
                    </option>
                <?php } ?>
                </select>
            </div>
            <button class="btn btn-filled" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir ajouter ce livre ?") ?>>Ajouter le livre</button>
            <?php if ($formData['created'] === true): ?>
                <span class="feedback-info">! Votre livre a bien été ajouté</span>
            <?php endif; ?>
        </div>
    </form>
</section>
<!-- Modale qui gère la prévisualisation de l'image à télécharger -->

<?php

/**
 * Template du code HTML et JS qui permet de gérer une fenêtre modale pour faciliter
 * l'expérience utilisateur lors de la sélection d'une image à uploader
 */
require(TEMPLATE_VIEW_PATH . 'modalUpload.php') ?>
