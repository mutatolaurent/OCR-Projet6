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
                    src="<?= $user[0]->getPhoto() ?>"
                    alt="image du profil de <?= $user[0]->getPseudo() ?>"
                />
                <!-- <a href="index.php?action=myAccount&zoom=viewAvatar">Modifier</a> -->
                <label for="avatar" class="link-upload">Modifier</label>
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
                        value="<?= isset($user[1]['error']['email']) ? $user[1]['credential']['email'] : $user[0]->getEmail() ?>"
                    />
                    <?php if (isset($user[1]['error']['email'])): ?>
                        <span class="text-error"><?= $user[1]['error']['email'] ?></span>
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
                        placeholder="******"
                        value="<?= isset($user[1]['error']['password']) ? $user[1]['credential']['password'] : '' ?>"
                    />
                    <?php if (isset($user[1]['error']['password'])): ?>
                        <span class="text-error"><?= $user[1]['error']['password'] ?></span>
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
                        value="<?= isset($user[1]['error']['pseudo']) ? $user[1]['credential']['pseudo'] : $user[0]->getPseudo() ?>"
                    />
                    <?php if (isset($user[1]['error']['pseudo'])): ?>
                        <span class="text-error"><?= $user[1]['error']['pseudo'] ?></span>
                    <?php endif; ?>
                </div>
                <button class="btn btn-empty" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir enregistrer ces modifications ?") ?>>Enregistrer</button>
                <?php if ($user[1]['updated'] === true): ?>
                    <span class="feedback-info">Vos informations ont bien été mises à jour</span>
                <?php endif; ?>

            <!-- </form> -->
        </div>
    </form>
    <!-- </section> -->

    <section class="mon-cpt-sect2">
    <table>
        <thead>
        <tr>
            <th>PHOTO</th>
            <th>TITRE</th>
            <th>AUTEUR</th>
            <th>DESCRIPTION</th>
            <th>DISPONIBILITE</th>
            <th>ACTION</th>
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
                        <td><span class="book-state-<?= $book->getIdState() ?>"><?= $book->getStateLabel() ?></span></td>
                        <td>
                            <a href="index.php?action=showBookForUpdate&id=<?= $book->getId() ?>" class="edit-link">Editer</a>
                            <a href="index.php?action=deleteBook&id=<?= $book->getId() ?>" class="delete-link" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer ce livre ?") ?>>Supprimer</a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
    </section>
    <a href="index.php?action=showBookForAdd" class="btn btn-filled" >Ajouter un livre</a>
</div>
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
