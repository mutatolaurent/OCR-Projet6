document.addEventListener("DOMContentLoaded", () => {
  // Sélectionne tous les inputs de type file
  const fileInputs = document.querySelectorAll('input[type="file"]');

  const modal = document.getElementById("preview-modal");
  const previewImg = document.getElementById("preview-image");
  const confirmBtn = document.getElementById("confirm-upload");
  const cancelBtn = document.getElementById("cancel-upload");

  // Types MIME autorisés
  const allowedTypes = ["image/jpeg", "image/png", "image/webp"];

  let currentInput = null; // Pour savoir quel input est en cours d’aperçu

  fileInputs.forEach((input) => {
    input.addEventListener("change", () => {
      const file = input.files[0];
      if (!file) return;

      currentInput = input; // On mémorise l’input concerné

      // Vérification du type MIME
      if (!allowedTypes.includes(file.type)) {
        alert("Format non autorisé. Formats acceptés : JPG, PNG, WEBP.");
        input.value = "";
        return;
      }

      // Vérification de la taille max
      const maxSize = parseInt(input.dataset.maxSize, 10);
      if (file.size > maxSize) {
        alert(
          "Fichier trop volumineux. Maximum autorisé : " +
            maxSize / 1_000_000 +
            " Mo.",
        );
        input.value = "";
        return;
      }

      // Lecture et affichage de l’aperçu
      const reader = new FileReader();
      reader.onload = (e) => {
        previewImg.src = e.target.result;
        modal.classList.remove("hidden");
      };
      reader.readAsDataURL(file);
    });
  });

  // Confirmer → on garde le fichier
  confirmBtn.addEventListener("click", () => {
    modal.classList.add("hidden");
  });

  // Annuler → on vide l’input concerné
  cancelBtn.addEventListener("click", () => {
    modal.classList.add("hidden");
    if (currentInput) {
      currentInput.value = "";
      currentInput = null;
    }
  });
});
