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

    <!-- <div class="modif-book-infos"> -->
    <form action="index.php?action=updateBook" method="POST" enctype="multipart/form-data" class="modif-book-infos">

        <div class="modif-book-infos-left">
            <p>Photo</p>
            <img
            src="<?= $books[0]->getPhoto() ?>"
            alt="Image associée au livre <?= $books[0]->getTitle() ?> de <?= $books[0]->getAuthor() ?>"
            />
            <label for="picture" class="link-upload">Modifier</label>
            <input
                type="file"
                name="picture"
                id="picture"
                accept="image/*"
                class="input-upload"
                data-max-size="<?= MAX_UPLOAD_BSIZE ?>"
            >
            <?php if (isset($books[1]['error']['picture'])): ?>
                <span class="text-error"><?= $books[1]['error']['picture'] ?></span>
            <?php endif; ?>
        </div>
        <div class="modif-book-infos-right">
            <!-- <form action="index.php?action=updateBook" method="POST"> -->

                <!-- Titre du livre -->
                <div class="champ-formulaire">
                    <label for="title">Titre</label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="<?= isset($books[1]['error']['title']) ? $books[1]['bookinfo']['title'] : $books[0]->getTitle() ?>"
                    />
                    <?php if (isset($books[1]['error']['title'])): ?>
                        <span class="text-error"><?= $books[1]['error']['title'] ?></span>
                    <?php endif; ?>
                </div>
                
                <!-- Auteur du livre -->
                <div class="champ-formulaire">
                    <label for="author">Auteur</label>
                    <input 
                        type="text" 
                        name="author" 
                        id="author" 
                        value="<?= isset($books[1]['error']['author']) ? $books[1]['bookinfo']['author'] : $books[0]->getAuthor() ?>" 
                    />
                    <?php if (isset($books[1]['error']['author'])): ?>
                        <span class="text-error"><?= $books[1]['error']['author'] ?></span>
                    <?php endif; ?>
                </div>

                <!-- Commentaires sur le livre -->
                <div class="champ-formulaire">
                    <label for="description">Commentaire</label>
                    <textarea
                        name="description"
                        id="description"
                        ><?= isset($books[1]['error']['description']) ? $books[1]['bookinfo']['description'] : $books[0]->getDescription() ?></textarea>
                    <?php if (isset($books[1]['error']['description'])): ?>
                        <span class="text-error"><?= $books[1]['error']['description'] ?></span>
                    <?php endif; ?>
                </div>

                <!-- Disponibilité du livre -->
                <div class="champ-formulaire">
                    <label for="idstate">Disponibilité</label>
                    <select name="idstate" id="idstate">
                    <?php foreach ($books[2] as $bookState) { ?>
                        <option 
                            value="<?= $bookState->getId() ?>" 
                            <?php (isset($books[1]['bookinfo']['idstate'])) ? $idState = $books[1]['bookinfo']['idstate'] : $idState = $books[0]->getIdState(); ?>
                            <?= ($bookState->getId() == $idState ? ' selected' : '') ?>
                            ><?= $bookState->getState() ?>
                        </option>
                    <?php } ?>
                    </select>
                </div>
                <button class="btn btn-filled" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir enregistrer ces modifications ?") ?>>Valider</button>
                <?php if ($books[1]['updated'] === true): ?>
                    <span class="feedback-info">! Vos informations ont bien été mises à jour</span>
                <?php endif; ?>
            <!-- </form> -->
        </div>
    <!-- </div> -->
    </form>
</section>
<!-- Modale qui gère la prévisualisation de l'image à télécharger -->
<div id="preview-modal" class="modal hidden">
    <div class="modal-content">
        <h3>Aperçu de l’image sélectionnée</h3>
        <img id="preview-image" src="" alt="Aperçu" />

        <div class="modal-buttons">
            <button id="confirm-upload" class="btn btn-filled">Confirmer</button>
            <button id="cancel-upload" class="btn btn-empty">Annuler</button>
        </div>
    </div>
</div>
<!-- Script JS qui gère la modale de prévisualisation de l'image à télécharger -->
<script src="js/uploadPreview.js"></script>


