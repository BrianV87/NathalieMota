(() => {
  const root = document.getElementById("nm-lightbox");
  if (!root) return;

  // --- Références DOM internes ---
  const overlay = root.querySelector(".nm-lightbox__overlay");
  const imgEl = root.querySelector(".nm-lightbox__img");
  const refEl = root.querySelector(".nm-lightbox__caption-ref");
  const catEl = root.querySelector(".nm-lightbox__caption-cat");
  const btnClose = root.querySelector(".nm-lightbox__close");
  const btnPrev = root.querySelector('[data-lightbox="prev"]');
  const btnNext = root.querySelector('[data-lightbox="next"]');

  let items = [];
  let index = 0;

  // --- Récupère toutes les photos présentes sur la page ---
  const collect = () => {
    items = [...document.querySelectorAll(".photo-card")]
      .map((card) => {
        const img = card.querySelector("img");
        const btn = card.querySelector('[data-lightbox="open"]');
        return {
          id: btn?.dataset.photoId,
          src: img?.currentSrc || img?.src,
          ref: btn?.dataset.photoRef || "",
          cat: btn?.dataset.photoCat || "",
        };
      })
      .filter((it) => it.id && it.src); // sécurité
  };

  // --- Affiche l'item à l'indice passé ---
  const show = (i) => {
    const it = items[i];
    if (!it) return;
    imgEl.src = it.src;
    imgEl.alt = `${it.ref || "Photo"} — ${it.cat || ""}`.trim();
    refEl.textContent = it.ref || "";
    catEl.textContent = it.cat || "";
  };

  // --- Ouvre la lightbox sur un ID donné ---
  const open = (id) => {
    if (!items.length) collect();
    index = items.findIndex((it) => it.id === id);
    if (index < 0) return;
    show(index);
    root.setAttribute("aria-hidden", "false");
    document.documentElement.style.overflow = "hidden";
  };

  // --- Ferme la lightbox ---
  const close = () => {
    root.setAttribute("aria-hidden", "true");
    document.documentElement.style.overflow = "";
    imgEl.removeAttribute("src");
  };

  // --- Navigation (suivante / précédente) ---
  const next = () => {
    if (!items.length) return;
    index = (index + 1) % items.length;
    show(index);
  };

  const prev = () => {
    if (!items.length) return;
    index = (index - 1 + items.length) % items.length;
    show(index);
  };

  // --- Ouvrir via clic sur bouton des cartes ---
  document.addEventListener("click", (e) => {
    const btn = e.target.closest('[data-lightbox="open"]');
    if (!btn) return;
    e.preventDefault();
    open(btn.dataset.photoId);
  });

  // --- Fermeture et navigation par UI ---
  overlay?.addEventListener("click", close);
  btnClose?.addEventListener("click", close);
  btnNext?.addEventListener("click", next);
  btnPrev?.addEventListener("click", prev);

  // --- Navigation clavier ---
  window.addEventListener("keydown", (e) => {
    if (root.getAttribute("aria-hidden") === "true") return;
    if (e.key === "Escape") close();
    else if (e.key === "ArrowRight") next();
    else if (e.key === "ArrowLeft") prev();
  });
})();
