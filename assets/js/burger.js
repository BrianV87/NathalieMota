document.addEventListener("DOMContentLoaded", () => {
  const burger = document.querySelector(".burger");
  const mobileMenu = document.getElementById("mobileMenu");
  const closeMenuBtn = document.querySelector(".mobile-menu-close");

  // Sécurité : on stoppe si l'un des éléments essentiels manque
  if (!burger || !mobileMenu || !closeMenuBtn) return;

  // --- OUVERTURE menu ---
  burger.addEventListener("click", () => {
    burger.classList.add("is-active");
    mobileMenu.classList.add("is-open");
    document.body.style.overflow = "hidden"; // empêche le scroll sous le menu
  });

  // --- FERMETURE via bouton X ---
  closeMenuBtn.addEventListener("click", () => {
    burger.classList.remove("is-active");
    mobileMenu.classList.remove("is-open");
    document.body.style.overflow = "";
  });

  // --- FERMETURE si clic sur le fond overlay du menu ---
  mobileMenu.addEventListener("click", (e) => {
    if (e.target === mobileMenu) {
      burger.classList.remove("is-active");
      mobileMenu.classList.remove("is-open");
      document.body.style.overflow = "";
    }
  });

  // --- FERMETURE automatique quand on clique un lien du menu ---
  mobileMenu.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      burger.classList.remove("is-active");
      mobileMenu.classList.remove("is-open");
      document.body.style.overflow = "";
    });
  });
});
