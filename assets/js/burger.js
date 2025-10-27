document.addEventListener("DOMContentLoaded", () => {
  const burger = document.querySelector(".burger");
  const mobileMenu = document.getElementById("mobileMenu");
  const closeMenuBtn = document.querySelector(".mobile-menu-close");

  if (!burger || !mobileMenu || !closeMenuBtn) return;

  burger.addEventListener("click", () => {
    burger.classList.add("is-active");
    mobileMenu.classList.add("is-open");
    document.body.style.overflow = "hidden";
  });

  closeMenuBtn.addEventListener("click", () => {
    burger.classList.remove("is-active");
    mobileMenu.classList.remove("is-open");
    document.body.style.overflow = "";
  });

  mobileMenu.addEventListener("click", (e) => {
    if (e.target === mobileMenu) {
      burger.classList.remove("is-active");
      mobileMenu.classList.remove("is-open");
      document.body.style.overflow = "";
    }
  });

  mobileMenu.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      burger.classList.remove("is-active");
      mobileMenu.classList.remove("is-open");
      document.body.style.overflow = "";
    });
  });
});
