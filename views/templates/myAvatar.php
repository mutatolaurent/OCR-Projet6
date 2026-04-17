<?php

/**
 * Template pour afficher et modifier la photo de profil de l'utilisateur
 *
 */
?>
<section class="modif-infos-main-sect">
    <a href="index.php?action=myAccount" class="return-link"
        ><img src="images/icones/retour.svg" alt="Lien retour" /><span>
            retour</span
        ></a
    >
    <h1>Modifier la photo de profil</h1>

    <div class="modif-book-infos">
        <div class="modif-book-infos-left">
            <p>Photo</p>
            <img
            src="<?= $user[0]->getPhoto() ?>"
            alt="Photo de profil de <?= $user[0]->getPseudo() ?>"
            />
        </div>
        <div class="modif-book-infos-right">
            <form action="index.php?action=updateMyAvatar" method="POST" enctype="multipart/form-data">
                <div class="champ-formulaire">
                    <label for="avatar">Changer votre photo de profil</label>
                    <input
                        type="file"
                        name="avatar"
                        id="avatar"
                        accept="image/*"
                        class="input-upload"
                    >
                    <span class="info-upload">2 MO maximum. Formats autorisés jpg, webp, png.</span>
                    <?php if (isset($user[1]['error']['avatar'])): ?>
                        <span class="text-error"><?= $user[1]['error']['avatar'] ?></span>
                    <?php endif; ?>

                </div>
                <button class="btn btn-filled" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir enregistrer ces modifications ?") ?>>Valider</button>
            </form>
        </div>
    </div>
</section>
