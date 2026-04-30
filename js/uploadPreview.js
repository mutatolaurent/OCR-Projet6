document.addEventListener("DOMContentLoaded", () => {
  const fileInputs = document.querySelectorAll('input[type="file"]');

  const modal = document.getElementById("preview-modal");
  const previewImg = document.getElementById("preview-image");
  const confirmBtn = document.getElementById("confirm-upload");
  const cancelBtn = document.getElementById("cancel-upload");

  const allowedTypes = ["image/jpeg", "image/png", "image/webp"];

  let currentInput = null;
  let lastFocusedElement = null;

  /* ------------------------------------------------------------
     1) Gestion du bouton "Ajouter une image" → déclenche l’input
     ------------------------------------------------------------ */
  const triggerUpload = document.getElementById("trigger-upload");

  if (triggerUpload) {
    triggerUpload.addEventListener("click", () => {
      // On cherche l’input lié : picture, avatar, etc.
      const input =
        document.getElementById("picture") || document.getElementById("avatar");

      if (input) {
        input.click();
      }
    });
  }

  /* ------------------------------------------------------------
    2) Focus trap (accessibilité)
     ------------------------------------------------------------ */
  function getFocusableElements(container) {
    return container.querySelectorAll(
      'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])',
    );
  }

  function activateFocusTrap() {
    const focusables = getFocusableElements(modal);
    const firstFocusable = focusables[0];
    const lastFocusable = focusables[focusables.length - 1];

    firstFocusable.focus();

    modal.addEventListener("keydown", (e) => {
      if (e.key !== "Tab") return;

      if (e.shiftKey) {
        if (document.activeElement === firstFocusable) {
          e.preventDefault();
          lastFocusable.focus();
        }
      } else {
        if (document.activeElement === lastFocusable) {
          e.preventDefault();
          firstFocusable.focus();
        }
      }
    });
  }

  function openModal() {
    lastFocusedElement = document.activeElement;
    modal.classList.remove("hidden");
    modal.removeAttribute("aria-hidden");
    // console.log("Open modal");

    // // Afficher les attributs HTML du modal
    // console.log("--- Attributs HTML du modal ---");
    // const attrs = modal.attributes;
    // for (let i = 0; i < attrs.length; i++) {
    //   console.log(`${attrs[i].name}: ${attrs[i].value}`);
    // }

    activateFocusTrap();
  }

  function closeModal() {
    modal.classList.add("hidden");
    modal.setAttribute("aria-hidden", "true");

    // Afficher les attributs HTML du modal
    // console.log("Close modal");
    // console.log("--- Attributs HTML du modal ---");
    // const attrs = modal.attributes;
    // for (let i = 0; i < attrs.length; i++) {
    //   console.log(`${attrs[i].name}: ${attrs[i].value}`);
    // }

    if (lastFocusedElement) {
      lastFocusedElement.focus();
    }
  }

  /* ------------------------------------------------------------
    3) Gestion du changement de fichier
     ------------------------------------------------------------ */
  fileInputs.forEach((input) => {
    input.addEventListener("change", () => {
      const file = input.files[0];
      if (!file) return;

      currentInput = input;

      if (!allowedTypes.includes(file.type)) {
        alert("Format non autorisé. Formats acceptés : JPG, PNG, WEBP.");
        input.value = "";
        return;
      }

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

      const reader = new FileReader();
      reader.onload = (e) => {
        previewImg.src = e.target.result;
        openModal();
      };
      reader.readAsDataURL(file);
    });
  });

  /* ------------------------------------------------------------
     4) Boutons de la modale
     ------------------------------------------------------------ */
  confirmBtn.addEventListener("click", () => {
    closeModal();
    if (currentInput) {
      const newSrc = previewImg.src;
      const targetImg = document.getElementById("current-img");
      if (targetImg) targetImg.src = newSrc;
    }
  });

  cancelBtn.addEventListener("click", () => {
    closeModal();
    if (currentInput) {
      currentInput.value = "";
      currentInput = null;
    }
  });

  /* ------------------------------------------------------------
     5) Fermeture avec Échap
     ------------------------------------------------------------ */
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && !modal.classList.contains("hidden")) {
      cancelBtn.click();
    }
  });
});
