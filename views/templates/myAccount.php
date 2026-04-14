<?php

/**
 * Template pour afficher le compte privé d'un utilisateur
 *
 */
?>
<div class="mon-cpt-main">
    <h1>Mon compte</h1>
    <section class="mon-cpt-sect1">
    <div class="mon-cpt-sect1-left">
        <div class="cpt-img-profil-container">
            <img
                class="cpt-img-profil"
                src="images/users/yvredelivres.jpg"
                alt="image profil YvreDeLivres"
            />
            <a href="#">Modifier</a>
        </div>

        <h2 class="cpt-owner">Alexlecture</h2>
        <p class="cpt-time">Membre depuis 1 an</p>
        <p class="cpt-bib">BIBLIOTHEQUE</p>
        <div class="cpt-count-container">
            <img
                class="cpt-count-img"
                src="images/icones/bibliotheque.svg"
                alt=""
            />
            <p class="cpt-count">10 livres</p>
        </div>
    </div>
    <div class="mon-cpt-sect1-right">
        <p>Vos informations personnelles</p>
        <form action="#" method="POST">
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
            <input
            type="password"
            name="password"
            id="password"
            required
            />
            <span class="text-error">Message en cas d'erreur</span>
        </div>
        <div class="champ-formulaire">
            <label for="pseudo">Pseudo</label>
            <input
            type="text"
            name="pseudo"
            id="pseudo"
            value="nathalire"
            required
            />
            <span class="text-error">Message en cas d'erreur</span>
        </div>
        <button class="btn btn-empty">Enregistrer</button>
        </form>
    </div>
    </section>

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
        <tr>
            <td>
                <a href="#">
                    <img
                    src="images/books/a-book-of-full-hope.jpg"
                    alt="A book of full hope"
                    />
                </a>
            </td>
            <td>
            A book of full hope A book of full hope A book of full hope
            </td>
            <td>Nathan Williams</td>
            <td>
            J'ai récemment plongé dans les pages de 'The Kinfolk Table'
            et j'ai été enchanté pa...
            </td>
            <td><span class="book-state-available">Disponible</span></td>
            <td>
            <a href="#" class="edit-link">Editer</a>
            <a href="#" class="delete-link">Supprimer</a>
            </td>
        </tr>
        <tr>
            <td>
            <a href="#">
                <img
                src="images/books/a-book-of-full-hope.jpg"
                alt="A book of full hope"
                />
            </a>
            </td>
            <td>
            A book of full hope A book of full hope A book of full hope
            </td>
            <td>Nathan Williams</td>
            <td>
            J'ai récemment plongé dans les pages de 'The Kinfolk Table'
            et j'ai été enchanté pa...
            </td>
            <td>
            <span class="book-state-unavailable">Indisponible</span>
            </td>
            <td>
            <a href="#" class="edit-link">Editer</a>
            <a href="#" class="delete-link">Supprimer</a>
            </td>
        </tr>
        </tbody>
    </table>
    </section>
</div>
