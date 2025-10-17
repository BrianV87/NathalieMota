document.addEventListener("DOMContentLoaded", () => {
  /* =====================================
     MODALE DE CONTACT
  ===================================== */
  const contactBtns = document.querySelectorAll("[data-modal='contact']");
  const modal = document.getElementById("contact-modal");
  const closeBtn = modal?.querySelector(".modal-close");
  const photoRefInput = modal?.querySelector("#photo-ref");

  // Ouvrir la modale
  contactBtns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();

      const photoRef = btn.getAttribute("data-photo-ref");
      if (photoRefInput && photoRef) {
        photoRefInput.value = photoRef;
      }

      modal.classList.add("is-open");
      document.body.style.overflow = "hidden";
    });
  });

  // Fermer la modale (bouton X)
  closeBtn?.addEventListener("click", () => {
    modal.classList.remove("is-open");
    document.body.style.overflow = "";
  });

  // Fermer si clic sur l’arrière-plan
  modal?.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.classList.remove("is-open");
      document.body.style.overflow = "";
    }
  });

  /* =====================================
     MENU BURGER MOBILE
  ===================================== */
  const burger = document.querySelector(".burger");
  const mobileMenu = document.getElementById("mobileMenu");
  const closeMenuBtn = document.querySelector(".mobile-menu-close");

  if (burger && mobileMenu && closeMenuBtn) {
    // Ouvrir le menu mobile
    burger.addEventListener("click", () => {
      burger.classList.add("is-active");
      mobileMenu.classList.add("is-open");
      document.body.style.overflow = "hidden"; // empêche le scroll du fond
    });

    // Fermer le menu (croix)
    closeMenuBtn.addEventListener("click", () => {
      burger.classList.remove("is-active");
      mobileMenu.classList.remove("is-open");
      document.body.style.overflow = "";
    });

    // Fermer si clic sur le fond rouge en dehors du menu
    mobileMenu.addEventListener("click", (e) => {
      if (e.target === mobileMenu) {
        burger.classList.remove("is-active");
        mobileMenu.classList.remove("is-open");
        document.body.style.overflow = "";
      }
    });

    // Fermer le menu après clic sur un lien
    const menuLinks = mobileMenu.querySelectorAll("a");
    menuLinks.forEach((link) => {
      link.addEventListener("click", () => {
        burger.classList.remove("is-active");
        mobileMenu.classList.remove("is-open");
        document.body.style.overflow = "";
      });
    });
  }
});
