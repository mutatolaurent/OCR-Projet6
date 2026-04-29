<?php

/**
 * Template pour afficher la page de modification des informations sur un livre
 *
 */
?>
<section class="modif-infos-main-sect">
    <?php if ($books[1]['updated'] === true): ?>
        <span class="feedback-info" role="status" >! Vos informations ont bien été mises à jour</span>
    <?php endif; ?>

    <a href="index.php?action=myAccount" class="return-link">
        <img src="images/icones/retour.svg" alt="" aria-hidden="true"/>
        <span>retour</span>
    </a>

    <h1>Modifier les informations</h1>

    <!-- <div class="modif-book-infos"> -->
    <form action="index.php?action=updateBook" method="POST" enctype="multipart/form-data" class="modif-book-infos">

        <div class="modif-book-infos-left">
            <p>Photo</p>
            <img
            src="<?= $books[0]->getPhoto() ?>"
            id="current-img"
            alt="Image associée au livre <?= $books[0]->getTitle() ?> de <?= $books[0]->getAuthor() ?>"
            />
            <!-- <label for="picture" class="link-upload">Modifier</label>    -->
            <button 
                type="button" 
                class="link-upload" 
                id="trigger-upload" 
                aria-controls="picture"
                aria-label="Modifier la photo, ouvre le sélecteur de fichiers">
                Modifier la photo
            </button>
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
                        aria-required=true
                        <?= isset($books[1]['error']['title']) ? 'value="'.$books[1]['bookinfo']['title'].'" aria-invalid="true" aria-describedby="title-error-msg"' : 'value="'.$books[0]->getTitle().'"' ?>
                    />
                    <?php if (isset($books[1]['error']['title'])): ?>
                        <span id="title-error-msg" class="text-error"><?= $books[1]['error']['title'] ?></span>
                    <?php endif; ?>
                </div>
                
                <!-- Auteur du livre -->
                <div class="champ-formulaire">
                    <label for="author">Auteur</label>
                    <input 
                        type="text" 
                        name="author" 
                        id="author"
                        aria-required=true
                        <?= isset($books[1]['error']['author']) ? 'value="'.$books[1]['bookinfo']['author'].'" aria-invalid="true" aria-describedby="author-error-msg"' : 'value="'.$books[0]->getAuthor().'"' ?>
                        value="<?= isset($books[1]['error']['author']) ? $books[1]['bookinfo']['author'] : $books[0]->getAuthor() ?>" 
                    />
                    <?php if (isset($books[1]['error']['author'])): ?>
                        <span id="author-error-msg" class="text-error"><?= $books[1]['error']['author'] ?></span>
                    <?php endif; ?>
                </div>

                <!-- Commentaires sur le livre -->
                <div class="champ-formulaire">
                    <label for="description">Commentaire</label>
                    <textarea
                        name="description"
                        id="description"
                        aria-required=true
                        <?= isset($books[1]['error']['description']) ? 'aria-invalid="true" aria-describedby="descr-error-msg"' : '' ?>
                        ><?= isset($books[1]['error']['description']) ? $books[1]['bookinfo']['description'] : $books[0]->getDescription() ?></textarea>
                    <?php if (isset($books[1]['error']['description'])): ?>
                        <span id="descr-error-msg" class="text-error"><?= $books[1]['error']['description'] ?></span>
                    <?php endif; ?>
                </div>

                <!-- Disponibilité du livre -->
                <div class="champ-formulaire">
                    <label for="idstate">Disponibilité</label>
                    <select name="idstate" id="idstate" aria-required="true>
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
            <!-- </form> -->
        </div>
    <!-- </div> -->
    </form>
</section>
<?php

/**
 * Template du code HTML et JS qui permet de gérer une fenêtre modale pour faciliter
 * l'expérience utilisateur lors de la sélection d'une image à uploader
 */
require(TEMPLATE_VIEW_PATH . 'modalUpload.php') ?>


