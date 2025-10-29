document.addEventListener("DOMContentLoaded", () => {
  // --- Sélection des éléments nécessaires ---
  const contactBtns = document.querySelectorAll("[data-modal='contact']");
  const modal = document.getElementById("contact-modal");
  const closeBtn = modal?.querySelector(".modal-close");
  const photoRefInput = modal?.querySelector("#photo-ref");

  // --- OUVERTURE de la modale (depuis un bouton contact) ---
  contactBtns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const photoRef = btn.getAttribute("data-photo-ref");

      // Si un bouton contient une ref → la pré-remplir dans le formulaire
      if (photoRefInput && photoRef) photoRefInput.value = photoRef;

      modal.classList.add("is-open");
      document.body.style.overflow = "hidden"; // bloque le scroll
    });
  });

  // --- Fermeture via bouton X ---
  closeBtn?.addEventListener("click", () => {
    modal.classList.remove("is-open");
    document.body.style.overflow = "";
  });

  // --- Fermeture si on clique dans l'overlay sombre ---
  modal?.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.classList.remove("is-open");
      document.body.style.overflow = "";
    }
  });
});
