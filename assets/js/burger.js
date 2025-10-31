document.addEventListener("DOMContentLoaded", () => {
  const burger = document.querySelector(".burger");
  const mobileMenu = document.getElementById("mobileMenu");
  const closeMenuBtn = document.querySelector(".mobile-menu-close");

  if (!burger || !mobileMenu || !closeMenuBtn) return;

  // --- OUVERTURE ---
  burger.addEventListener("click", () => {
    burger.classList.add("is-active");
    mobileMenu.classList.add("is-open");
    document.body.style.overflow = "hidden";
  });

  // --- FERMETURE via bouton X ---
  closeMenuBtn.addEventListener("click", () => {
    burger.classList.remove("is-active");
    mobileMenu.classList.remove("is-open");
    document.body.style.overflow = "";
  });

  // --- FERMETURE si clic sur fond overlay ---
  mobileMenu.addEventListener("click", (e) => {
    if (e.target === mobileMenu) {
      burger.classList.remove("is-active");
      mobileMenu.classList.remove("is-open");
      document.body.style.overflow = "";
    }
  });

  // --- FERMETURE si clic sur lien ---
  mobileMenu.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      burger.classList.remove("is-active");
      mobileMenu.classList.remove("is-open");
      document.body.style.overflow = "";
    });
  });

  // --- RESET si on repasse en desktop ---
  window.addEventListener("resize", () => {
    if (window.innerWidth > 1024) {
      burger.classList.remove("is-active");
      mobileMenu.classList.remove("is-open");
      document.body.style.overflow = "";
    }
  });
});
