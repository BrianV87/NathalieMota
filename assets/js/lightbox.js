/**
 * =========================================================
 *  LIGHTBOX — Visionneuse plein écran
 * =========================================================
 *  - Affiche les photos en grand depuis les cartes `.photo-card`
 *  - Navigation clavier et boutons (← / →)
 *  - Compatible avec AJAX (recollecte automatique)
 *  - Compatible multi-boutons (œil + fullscreen)
 *  - Chargée en toute sécurité (DOMContentLoaded)
 * =========================================================
 */

document.addEventListener("DOMContentLoaded", () => {
  // ---------------------------------------------------------
  // VARIABLES GLOBALES — (références DOM + état courant)
  // ---------------------------------------------------------
  let root, overlay, imgEl, refEl, catEl, btnClose, btnPrev, btnNext;
  let items = []; // Liste des photos (objets {id, src, ref, cat})
  let index = 0; // Index de la photo actuellement affichée

  // =========================================================
  // INITIALISATION DE LA LIGHTBOX (résolution du DOM)
  // =========================================================
  function ensureLightboxDOM() {
    // Si déjà en cache → inutile de refaire
    if (root && overlay && imgEl && refEl && catEl) return true;

    // Récupération du container global
    root = document.getElementById("nm-lightbox");
    if (!root) {
      console.error(
        "❌ Lightbox non trouvée dans le DOM (#nm-lightbox). Assure-toi qu’elle est incluse dans footer.php via get_template_part('template-parts/lightbox')."
      );
      return false;
    }

    // Sélection des sous-éléments internes
    overlay = root.querySelector(".nm-lightbox__overlay");
    imgEl = root.querySelector(".nm-lightbox__img");
    refEl = root.querySelector(".nm-lightbox__caption-ref");
    catEl = root.querySelector(".nm-lightbox__caption-cat");
    btnClose = root.querySelector(".nm-lightbox__close");
    btnPrev = root.querySelector('[data-lightbox="prev"]');
    btnNext = root.querySelector('[data-lightbox="next"]');

    // Écouteurs de navigation / fermeture
    overlay?.addEventListener("click", close);
    btnClose?.addEventListener("click", close);
    btnNext?.addEventListener("click", next);
    btnPrev?.addEventListener("click", prev);

    return true;
  }

  // =========================================================
  // COLLECTE DES PHOTOS — (analyse du DOM)
  // =========================================================
  function collect() {
    items = [...document.querySelectorAll(".photo-card")]
      .map((card) => {
        const img = card.querySelector(".photo-card__media img"); // ← image principale
        const btn = card.querySelector('[data-lightbox="open"]'); // ← bouton fullscreen
        if (!img || !btn) return null;

        return {
          id: btn.dataset.photoId, // ID WP de la photo
          src: img.currentSrc || img.src, // URL d’affichage
          ref: btn.dataset.photoRef || "", // Référence photo
          cat: btn.dataset.photoCat || "", // Catégorie
        };
      })
      .filter(Boolean); // Supprime les null
  }

  // =========================================================
  // AFFICHAGE D’UNE PHOTO DANS LA LIGHTBOX
  // =========================================================
  function show(i) {
    const it = items[i];
    if (!it || !imgEl) return;
    imgEl.src = it.src;
    imgEl.alt = `${it.ref || "Photo"} — ${it.cat || ""}`.trim();
    refEl.textContent = it.ref || "";
    catEl.textContent = it.cat || "";
  }

  // =========================================================
  // OUVERTURE DE LA LIGHTBOX
  // =========================================================
  function openById(id) {
    if (!ensureLightboxDOM()) return; // Récupère le DOM si besoin
    collect(); // Recollecte (utile après AJAX)

    // Recherche de la photo dans la liste
    const i = items.findIndex((it) => String(it.id) === String(id));
    if (i < 0) {
      console.warn(
        "❌ Lightbox : ID introuvable :",
        id,
        "→ IDs connus :",
        items.map((it) => it.id)
      );
      return;
    }

    // Mise à jour de l’état + affichage
    index = i;
    show(index);
    root.setAttribute("aria-hidden", "false");
    document.documentElement.style.overflow = "hidden"; // Bloque le scroll page
  }

  // =========================================================
  // FERMETURE DE LA LIGHTBOX
  // =========================================================
  function close() {
    if (!root) return;
    root.setAttribute("aria-hidden", "true");
    document.documentElement.style.overflow = "";
    if (imgEl) imgEl.removeAttribute("src");
  }

  // =========================================================
  // NAVIGATION (flèches et clavier)
  // =========================================================
  function next() {
    if (!items.length) return;
    index = (index + 1) % items.length;
    show(index);
  }

  function prev() {
    if (!items.length) return;
    index = (index - 1 + items.length) % items.length;
    show(index);
  }

  // Navigation clavier (← → Échap)
  window.addEventListener("keydown", (e) => {
    if (!root || root.getAttribute("aria-hidden") === "true") return;
    if (e.key === "Escape") close();
    if (e.key === "ArrowRight") next();
    if (e.key === "ArrowLeft") prev();
  });

  // =========================================================
  // ÉCOUTEUR GLOBAL DE CLIC (délégation)
  // =========================================================
  document.addEventListener("click", (e) => {
    const btn = e.target.closest('[data-lightbox="open"]');
    if (!btn) return; // Pas un bouton lié à la lightbox

    e.preventDefault(); // Empêche le clic par défaut
    e.stopPropagation();

    // Récupération de l’ID (direct ou via carte parente)
    let id = btn.dataset.photoId;
    if (!id) {
      const card = btn.closest(".photo-card");
      id = card?.dataset.id;
    }
    if (!id) {
      console.error("❌ Aucune photo ID trouvée sur le bouton/carte.");
      return;
    }

    openById(id);
  });

  // =========================================================
  // RECOLLECTE APRÈS UN CHARGEMENT AJAX
  // =========================================================
  document.addEventListener("photosUpdated", collect);

  // =========================================================
  // INITIALISATION
  // =========================================================
  collect(); // Charge les photos existantes dès le départ
  console.log("✅ Lightbox initialisée avec", items.length, "photos");
});
