document.addEventListener("DOMContentLoaded", () => {
  const loadMoreBtn = document.querySelector("#load-more.btn-load-more");
  const photoList = document.querySelector(".js-photo-list");
  const dropdowns = document.querySelectorAll(".dropdown");

  if (!loadMoreBtn || !photoList) {
    console.error("Bouton ou liste de photos non trouvé.");
    return;
  }

  // --- État global courant des filtres ---
  const state = {
    categorie: "",
    format: "",
    ordre: "recentes",
  };

  let currentPage = 1;
  let loading = false;
  const initialPerPage = 8;
  const postsPerPage = 2;

  // --- Lit les filtres depuis l'URL propre (/categorie/slug/...) ---
  function readParamsFromURL() {
    const pathParts = window.location.pathname.split("/").filter(Boolean);
    const categorieIndex = pathParts.indexOf("categorie");
    const formatIndex = pathParts.indexOf("format");
    const ordreIndex = pathParts.indexOf("ordre");

    state.categorie =
      categorieIndex !== -1 ? pathParts[categorieIndex + 1] : "";
    state.format = formatIndex !== -1 ? pathParts[formatIndex + 1] : "";
    state.ordre = ordreIndex !== -1 ? pathParts[ordreIndex + 1] : "recentes";

    applyStateToUI();
  }

  // --- Affiche l'état dans les dropdowns ---
  function applyStateToUI() {
    dropdowns.forEach((dropdown) => {
      const key = dropdown.dataset.filter;
      const label = dropdown.querySelector(".dropdown__label");
      const items = dropdown.querySelectorAll(".dropdown__item");
      let selectedItem = null;

      items.forEach((item) => {
        if (String(item.dataset.value || "") === String(state[key])) {
          selectedItem = item;
        }
      });

      const defaults = {
        categorie: "Catégories",
        format: "Formats",
        ordre: "Trier par",
      };

      label.textContent = selectedItem
        ? selectedItem.textContent.trim()
        : defaults[key];
    });
  }

  // --- Écouteurs dropdowns (ouverture, choix item, reload) ---
  function setupDropdowns() {
    dropdowns.forEach((dropdown) => {
      const toggle = dropdown.querySelector(".dropdown__toggle");
      const items = dropdown.querySelectorAll(".dropdown__item");
      const key = dropdown.dataset.filter;

      toggle.addEventListener("click", (e) => {
        e.stopPropagation();
        document.querySelectorAll(".dropdown.is-open").forEach((d) => {
          if (d !== dropdown) d.classList.remove("is-open");
        });
        dropdown.classList.toggle("is-open");
      });

      items.forEach((item) => {
        item.addEventListener("click", (e) => {
          e.preventDefault();
          state[key] = item.dataset.value || "";
          updateURL();
          dropdown.classList.remove("is-open");
          applyStateToUI();
          currentPage = 1;
          loadPage(true); // replace la liste
        });
      });
    });
  }

  // --- Met à jour l'URL propre selon les filtres actifs ---
  function updateURL() {
    let basePath = window.location.origin;
    const pathSegments = [];

    if (state.categorie) pathSegments.push("categorie", state.categorie);
    if (state.format) pathSegments.push("format", state.format);
    if (state.ordre !== "recentes") pathSegments.push("ordre", state.ordre);

    const newPath = pathSegments.length ? `/${pathSegments.join("/")}/` : "/";
    history.replaceState(null, "", basePath + newPath);
  }

  // --- Charge les photos (AJAX) : replace ou append ---
  function loadPage(replace = false) {
    if (loading) return;
    loading = true;
    loadMoreBtn.textContent = "Chargement...";
    loadMoreBtn.disabled = true;

    const offset = replace
      ? 0
      : document.querySelectorAll(".photo-block").length;
    const ppp = replace ? initialPerPage : postsPerPage;
    const usp = new URLSearchParams();

    usp.set("action", "load_more_photos");
    usp.set("ppp", String(ppp));
    usp.set("offset", String(offset));
    usp.set("ordre", state.ordre);

    if (state.categorie) usp.set("categorie", state.categorie);
    if (state.format) usp.set("format", state.format);

    const ajaxUrl =
      window.nm_ajax && nm_ajax.ajax_url
        ? nm_ajax.ajax_url
        : "/wp-admin/admin-ajax.php";
    const url = `${ajaxUrl}?${usp.toString()}`;

    fetch(url, { credentials: "same-origin" })
      .then((r) => {
        if (!r.ok) throw new Error(`HTTP ${r.status}`);
        return r.text();
      })
      .then((html) => {
        const trimmed = (html || "").trim();
        if (replace) {
          photoList.innerHTML =
            trimmed || "<p class='no-results'>Aucune photo trouvée.</p>";
          currentPage = 1;
        } else if (trimmed) {
          photoList.insertAdjacentHTML("beforeend", trimmed);
          currentPage++;
        }

        // Cache le bouton si plus rien à charger
        const temp = document.createElement("div");
        temp.innerHTML = trimmed;
        const count = temp.querySelectorAll(".photo-block").length;
        loadMoreBtn.style.display = count === 0 ? "none" : "";
      })
      .catch((err) => console.error("Erreur AJAX :", err))
      .finally(() => {
        loadMoreBtn.textContent = "Charger plus";
        loadMoreBtn.disabled = false;
        loading = false;
      });
  }

  // --- Fermer tous les dropdowns si clic en dehors ---
  document.addEventListener("click", () => {
    document.querySelectorAll(".dropdown.is-open").forEach((d) => {
      d.classList.remove("is-open");
    });
  });

  // --- INIT ---
  readParamsFromURL();
  applyStateToUI();
  setupDropdowns();
  loadMoreBtn.addEventListener("click", () => loadPage(false));

  // Charger si filtres actifs
  if (state.categorie || state.format || state.ordre !== "recentes") {
    loadPage(true);
  }
});
