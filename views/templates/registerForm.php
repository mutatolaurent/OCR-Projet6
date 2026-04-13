<?php

/**
 * Template pour afficher le formulaire de connexion
 */
?>
<section class="insc-main-sect">
    <div class="insc-part-left">
    <h1>Connexion</h1>
    <form action="#" method="POST">
        <div class="champ-formulaire">
            <label for="pseudo">Pseudo</label>
            <input
                type="text"
                name="pseudo"
                id="pseudo"
                value="nathalire"
                required
            />
            <span class="text-error"></span>
        </div>
        <div class="champ-formulaire">
            <label for="email">Adresse mail</label>
            <input
                class="input-error"
                type="text"
                name="email"
                id="email"
                value="nathalie@gmail.com"
                required
            />
            <span class="text-error">Message en cas d'erreur</span>
        </div>
        <div class="champ-formulaire">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required />
            <span class="text-error">Message en cas d'erreur</span>
        </div>
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
