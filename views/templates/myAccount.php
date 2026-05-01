<?php

/**
 * Template pour afficher le compte privé d'un utilisateur
 *
 */
?>
<div class="mon-cpt-main">
    <h1>Mon compte</h1>
    <!-- <section> -->
    <!-- <section class="mon-cpt-sect1"> -->
    <form action="index.php?action=updateMyAccount" method="POST" enctype="multipart/form-data" class="mon-cpt-sect1">

        <div class="mon-cpt-sect1-left">
            <div class="cpt-img-profil-container">
                <img
                    class="cpt-img-profil"
                    id="current-img"
                    src="<?= $user[0]->getPhoto() !== null ? $user[0]->getPhoto() : 'images/users/placeholder.png'?>"
                    alt="image du profil de <?= $user[0]->getPseudo() ?>"
                />
                <!-- <a href="index.php?action=myAccount&zoom=viewAvatar">Modifier</a> -->
                <!-- <label for="avatar" class="link-upload">Modifier</label> -->
                <button 
                    type="button" 
                    class="link-upload" 
                    id="trigger-upload" 
                    aria-controls="avatar"
                    aria-label="Modifier une image, ouvre le sélecteur de fichiers">
                    Modifier
                </button>
                <label for="avatar" class="sr-only">Modifier votre avatar</label>
                <input
                    type="file"
                    name="avatar"
                    id="avatar"
                    accept="image/*"
                    class="input-upload"
                >
                <?php if (isset($user[1]['error']['avatar'])): ?>
                    <span class="info-upload"><?= $user[1]['error']['avatar'] ?></span>
                <?php endif; ?>
            </div>

            <h2 class="cpt-owner"><?= $user[0]->getPseudo() ?></h2>
            <p class="cpt-time">Membre depuis <?= Utils::formatDateDiff($user[0]->getCreatedAt()) ?></p>
            <p class="cpt-bib">BIBLIOTHEQUE</p>
            <div class="cpt-count-container">
                <img
                    class="cpt-count-img"
                    src="images/icones/bibliotheque.svg"
                    alt=""
                    aria-hidden="true"
                />
                <p class="cpt-count"><?= count($user[0]->getBooks()) ?> livres</p>
            </div>
        </div>
        <div class="mon-cpt-sect1-right">
            <p>Vos informations personnelles</p>
            <!-- <form action="index.php?action=updateMyAccount" method="POST"> -->

                <!-- Adresse mail -->
                <div class="champ-formulaire">
                    <label for="email">Adresse mail</label>
                    <input
                        class="<?= isset($user[1]['error']['email']) ? 'input-error' : '' ?>"
                        type="text"
                        name="email"
                        id="email"
                        aria-required="true"
                        value="<?= isset($user[1]['error']['email']) ? $user[1]['credential']['email'] : $user[0]->getEmail() ?>"
                        <?= isset($user[1]['error']['email']) ? 'aria-invalid="true" aria-describedby="email-error-msg"' : '' ?>
                    />
                    <?php if (isset($user[1]['error']['email'])): ?>
                        <span id="email-error-msg" class="text-error"><?= $user[1]['error']['email'] ?></span>
                    <?php endif; ?>
                </div>

                <!-- Mot de passe -->
                <div class="champ-formulaire">
                    <label for="password">Mot de passe</label>
                    <input
                        class="<?= isset($user[1]['error']['password']) ? 'input-error' : '' ?>"
                        type="password"
                        name="password"
                        id="password"
                        aria-required="true"
                        placeholder="******"
                        value="<?= isset($user[1]['error']['password']) ? $user[1]['credential']['password'] : '' ?>"
                        <?= isset($user[1]['error']['password']) ? 'aria-invalid="true" aria-describedby="password-error-msg"' : '' ?>
                    />
                    <?php if (isset($user[1]['error']['password'])): ?>
                        <span id="password-error-msg" class="text-error"><?= $user[1]['error']['password'] ?></span>
                    <?php endif; ?>
                </div>

                <!-- Pseudo -->
                <div class="champ-formulaire">
                    <label for="pseudo">Pseudo</label>
                    <input
                        class="<?= isset($user[1]['error']['pseudo']) ? 'input-error' : '' ?>"
                        type="text"
                        name="pseudo"
                        id="pseudo"
                        aria-required="true"
                        value="<?= isset($user[1]['error']['pseudo']) ? $user[1]['credential']['pseudo'] : $user[0]->getPseudo() ?>"
                        <?= isset($user[1]['error']['pseudo']) ? 'aria-invalid="true" aria-describedby="pseudo-error-msg"' : '' ?>
                    />
                    <?php if (isset($user[1]['error']['pseudo'])): ?>
                        <span id="pseudo-error-msg" class="text-error"><?= $user[1]['error']['pseudo'] ?></span>
                    <?php endif; ?>
                </div>
                <!-- <button class="btn btn-empty" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir enregistrer ces modifications ?") ?>>Enregistrer</button> -->
                <button type="submit" class="btn btn-empty">Enregistrer</button>
                <!-- <?php if ($user[1]['updated'] === true): ?>
                    <span class="feedback-info">Vos informations ont bien été mises à jour</span>
                <?php endif; ?> -->

            <!-- </form> -->
        </div>
    </form>
    <!-- </section> -->

    <section class="mon-cpt-sect2">
        <h2 class="sr-only">Liste des livres associés à ce compte utilisateur</h2>
    <table>
        <caption class="sr-only">
            Liste des livres partagés par le compte utilisateur
        </caption>
        <thead>
        <tr>
            <th scope="col">PHOTO</th>
            <th scope="col">TITRE</th>
            <th scope="col">AUTEUR</th>
            <th scope="col">DESCRIPTION</th>
            <th scope="col">DISPONIBILITE</th>
            <th scope="col">ACTION</th>
        </tr>
        </thead>
        <tbody>
            <?php if ($user[0]->getBooks() !== false) { ?>     
                <?php foreach ($user[0]->getBooks() as $book) { ?>
                    <tr>
                        <td>            
                            <img
                            src="<?= $book->getPhoto() ? $book->getPhoto() : 'images/books/placeholder.png' ?>"
                            alt="Photo associée au livre <?= $book->getTitle() ?> de <?= $book->getAuthor() ?> - lien vers la page d'informations sur ce livre"
                            />
                        </td>
                        <td><?= $book->getTitle() ?></td>
                        <td><?= $book->getAuthor() ?></td>

                        <td class="description-td"><?= mb_substr($book->getDescription(), 0, 100).'...' ?></td>
                        <td><span class="book-state book-state-<?= $book->getIdState() ?>"><?= $book->getStateLabel() ?></span></td>
                        <td>
                            <a href="index.php?action=showBookForUpdate&id=<?= $book->getId() ?>" class="action-link edit-link">Editer</a>
                            <a href="index.php?action=deleteBook&id=<?= $book->getId() ?>" class="action-link delete-link" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer ce livre ?") ?>>Supprimer</a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
    </section>
    <a href="index.php?action=showBookForAdd" class="btn btn-filled" >Ajouter un livre</a>
</div>
<?php

/**
 * Template du code HTML et JS qui permet de gérer une fenêtre modale pour faciliter
 * l'expérience utilisateur lors de la sélection d'une image à uploader
 */
require(TEMPLATE_VIEW_PATH . 'modalUpload.php') ?>

<!-- Script JS qui gère la modale de prévisualisation de l'image à télécharger -->
<script src="js/uploadPreview.js"></script>

<!-- Script JS qui gère l'affichage du message de succès de l'enregistrement d'un formulaire -->
<script src="js/displaySuccessBanner.js"></script>
