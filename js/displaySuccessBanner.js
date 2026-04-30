window.addEventListener("DOMContentLoaded", () => {
  const toast = document.getElementById("success-toast");

  if (toast) {
    // 1. Un léger délai pour laisser la page s'afficher
    setTimeout(() => {
      toast.classList.add("is-visible");
      toast.focus(); // Accessibilité : le lecteur d'écran lit le message
    }, 100);

    // 2. Disparition automatique après 4 secondes
    setTimeout(() => {
      toast.classList.remove("is-visible");
      toast.classList.add("is-hidden");
    }, 4000);
  }
});
