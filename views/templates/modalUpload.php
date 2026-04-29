<?php
/* Modale qui gère la prévisualisation de l'image à télécharger */
?>
<div
    id="preview-modal"
    class="modal hidden"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-title"
    aria-describedby="modal-desc"
    aria-hidden="true"
>
    <div class="modal-content">
        <p id="modal-title">Aperçu de l’image sélectionnée</p>
        <p id="modal-desc">
            Vérifiez l’image avant de confirmer l’envoi.
        </p>
        <img id="preview-image" src="images/books/placeholder.png" alt="Aperçu de l’image sélectionnée" />
        <div class="modal-buttons">
            <button id="confirm-upload" class="btn btn-filled">Confirmer</button>
            <button id="cancel-upload" class="btn btn-empty">Annuler</button>
        </div>
    </div>
</div>

<!-- Script JS qui gère la modale de prévisualisation de l'image à télécharger -->
<script src="js/uploadPreview.js"></script>