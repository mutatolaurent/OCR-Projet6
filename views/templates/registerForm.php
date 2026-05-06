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
                aria-required="true"
                <?= isset($formData['credential']['pseudo']) ? 'value="'.$formData['credential']['pseudo'].'"' : '' ?>
                <?= isset($formData['error']['pseudo']) ? ' role="alert" aria-invalid="true" aria-describedby="pseudo-error-msg"' : '' ?>
            />
            <?php if (isset($formData['error']['pseudo'])): ?>
                <span id="pseudo-error-msg" class="text-error"><?= $formData['error']['pseudo'] ?></span>
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
                placeholder="nom@exemple.com"
                aria-required="true"
                <?= isset($formData['credential']['email']) ? 'value="'.$formData['credential']['email'].'"' : '' ?>
                <?= isset($formData['error']['email']) ? ' role="alert" aria-invalid="true" aria-describedby="email-error-msg"' : '' ?>
            />
            <?php if (isset($formData['error']['email'])): ?>
                <span id="email-error-msg" class="text-error"><?= $formData['error']['email'] ?></span>
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
                placeholder="Au moins <?= PASSWORD_MIN_LENGTH ?> caractères"
                aria-required="true"
                <?= isset($formData['credential']['password']) ? 'value="'.$formData['credential']['password'].'"' : '' ?>
                <?= isset($formData['error']['password']) ? ' role="alert" aria-invalid="true" aria-describedby="password-error-msg"' : '' ?>
            />
            <?php if (isset($formData['error']['password'])): ?>
                <span id="password-error-msg" class="text-error"><?= $formData['error']['password'] ?></span>
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
