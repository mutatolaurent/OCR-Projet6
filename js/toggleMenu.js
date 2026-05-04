document.addEventListener("DOMContentLoaded", () => {
  const burger = document.querySelector(".burger-menu");
  const nav = document.querySelector(".navbar");

  burger.addEventListener("click", () => {
    const isOpen = nav.classList.toggle("is-open");

    // Mise à jour de l'attribut ARIA pour les lecteurs d'écran
    burger.setAttribute("aria-expanded", isOpen);

    // Optionnel : Changer l'icône si on a une icône fermer
    // burger.querySelector('img').src = isOpen ? 'icon-close.svg' : 'icon-menu.svg';
  });
});
