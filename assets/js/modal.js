document.addEventListener("DOMContentLoaded", () => {
  const contactBtns = document.querySelectorAll("[data-modal='contact']");
  const modal = document.getElementById("contact-modal");
  const closeBtn = modal?.querySelector(".modal-close");
  const photoRefInput = modal?.querySelector("#photo-ref");

  contactBtns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const photoRef = btn.getAttribute("data-photo-ref");
      if (photoRefInput && photoRef) photoRefInput.value = photoRef;
      modal.classList.add("is-open");
      document.body.style.overflow = "hidden";
    });
  });

  closeBtn?.addEventListener("click", () => {
    modal.classList.remove("is-open");
    document.body.style.overflow = "";
  });

  modal?.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.classList.remove("is-open");
      document.body.style.overflow = "";
    }
  });
});
