document.addEventListener("DOMContentLoaded", () => {
  const counter = document.querySelector(".msg-counter");

  async function updateUnreadCount() {
    try {
      const response = await fetch("index.php?action=getUnreadMessage");

      const data = await response.json();

      if (data.count > 0) {
        counter.textContent = data.count;
        counter.classList.add("active");
      } else {
        counter.classList.remove("active");
      }
    } catch (error) {
      console.error("Erreur récupération messages non lus :", error);
    }
  }

  // Mise à jour immédiate
  updateUnreadCount();

  // Mise à jour toutes les 10 secondes
  setInterval(updateUnreadCount, 10000);
});
