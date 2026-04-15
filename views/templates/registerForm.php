<?php

/**
 * Template pour afficher le formulaire d'inscription
 */
?>
<section class="insc-main-sect">
    <div class="insc-part-left">
    <h1>Inscription</h1>
    <form action="index.php?action=registerUser" method="POST">

        <!-- Champ de saisie du pseudo -->
        <div class="champ-formulaire">
            <label for="pseudo">Pseudo</label>
            <input
                <?= isset($formData['error']['pseudo']) ? 'class="input-error"' : '' ?>
                type="text"
                name="pseudo"
                id="pseudo"
                placeholder="ex: YvreDeLivres (<?= PSEUDO_MIN_LENGTH ?> caractères minimum)"
                <?= isset($formData['credential']['pseudo']) ? 'value="'.$formData['credential']['pseudo'].'"' : '' ?>
            />
            <?php if (isset($formData['error']['pseudo'])): ?>
                <span class="text-error"><?= $formData['error']['pseudo'] ?></span>
            <?php endif; ?>
        </div>

        <!-- Champ de saisie de l'adresse email -->
        <div class="champ-formulaire">
            <label for="email">Adresse mail</label>
            <input
                <?= isset($formData['error']['email']) ? 'class="input-error"' : '' ?>
                type="text"
                name="email"
                id="email"
                placeholder="ex: alex.durand@orange.fr"
                <?= isset($formData['credential']['email']) ? 'value="'.$formData['credential']['email'].'"' : '' ?>
            />
            <?php if (isset($formData['error']['email'])): ?>
                <span class="text-error"><?= $formData['error']['email'] ?></span>
            <?php endif; ?>
        </div>
        <!-- Champ de saisie du mot de passe -->
        <div class="champ-formulaire">
            <label for="password">Mot de passe</label>
            <input
                <?= isset($formData['error']['password']) ? 'class="input-error"' : '' ?>
                type="password" 
                name="password" 
                id="password"
                placeholder="<?= PASSWORD_MIN_LENGTH ?> caractères minimum"
                <?= isset($formData['credential']['password']) ? 'value="'.$formData['credential']['password'].'"' : '' ?>
            />
            <?php if (isset($formData['error']['password'])): ?>
                <span class="text-error"><?= $formData['error']['password'] ?></span>
            <?php endif; ?>
        </div>

        <!-- Bouton de soumission du formulaire -->
        <button class="btn btn-filled">S'inscrire</button>

    </form>
    <span
        >Déjà inscrit ?
        <a href="index.php?action=connectionForm" class="edit-link">Connectez vous</a></span
    >
    </div>
    <div class="insc-part-right">
        <img src="images/inscription/bibliotheque.jpg" alt="" />
    </div>
</section>
