document.addEventListener("DOMContentLoaded", () => {
  // --- Sélection des boutons (ceux avec data-modal ET le lien du menu) ---
  const contactBtns = document.querySelectorAll(
    "[data-modal='contact'], a[href='#contact']"
  );
  const modal = document.getElementById("contact-modal");
  const closeBtn = modal?.querySelector(".modal-close");
  const photoRefInput = modal?.querySelector("#photo-ref");

  // --- OUVERTURE de la modale ---
  contactBtns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault(); // empêche le scroll vers #contact

      const photoRef = btn.getAttribute("data-photo-ref");
      if (photoRefInput && photoRef) photoRefInput.value = photoRef;

      modal.classList.add("is-open");
      document.body.style.overflow = "hidden";
    });
  });

  // --- FERMETURE via bouton X ---
  closeBtn?.addEventListener("click", () => {
    modal.classList.remove("is-open");
    document.body.style.overflow = "";
  });

  // --- FERMETURE si clic sur fond sombre ---
  modal?.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.classList.remove("is-open");
      document.body.style.overflow = "";
    }
  });
});
