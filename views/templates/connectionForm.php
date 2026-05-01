<?php

/**
 * Template pour afficher le formulaire de connexion
 */
?>
<section class="insc-main-sect">
    <div class="insc-part-left">
        <h1>Connexion</h1>
        <form action="index.php?action=connectUser" method="POST">

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
                    <?= isset($formData['error']['email']) ? 'aria-invalid="true" aria-describedby="email-error-msg"' : '' ?>
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
                    <?= isset($formData['error']['password']) ? 'aria-invalid="true" aria-describedby="password-error-msg"' : '' ?>
                />
                <?php if (isset($formData['error']['password'])): ?>
                    <span id="password-error-msg" class="text-error"><?= $formData['error']['password'] ?></span>
                <?php endif; ?>
            </div>

            <!-- Bouton de soumission du formulaire -->
            <button class="btn btn-filled" <?= isset($formData['error']['auth']) ? 'aria-describedby="auth-error"' : '' ?>>Se connecter</button>
            <?php if (isset($formData['error']['auth'])): ?>
                <span id="auth-error" class="text-error auth-error" role="alert" aria-atomic="true"><?= $formData['error']['auth'] ?></span>
            <?php endif; ?>

        </form>
        <span
            >Pas de compte ?
            <a href="index.php?action=registerForm" class="edit-link">Inscrivez vous</a></span
        >
    </div>
    <div class="insc-part-right">
        <img src="images/inscription/bibliotheque.jpg" alt="" />
    </div>
</section>
