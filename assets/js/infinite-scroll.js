document.addEventListener("DOMContentLoaded", () => {
  const loadMoreBtn = document.querySelector("#load-more.btn-load-more");
  const photoList = document.querySelector(".js-photo-list");

  if (!loadMoreBtn || !photoList) return;

  let currentPage = 1;
  const postsPerPage = 2;
  let loading = false;

  loadMoreBtn.addEventListener("click", () => {
    if (loading) return;
    loading = true;
    loadMoreBtn.textContent = "Chargement...";

    fetch(
      `${nm_ajax.ajax_url}?action=load_more_photos&page=${
        currentPage + 1
      }&ppp=${postsPerPage}&exclude=${[
        ...document.querySelectorAll(".photo-block"),
      ]
        .map((el) => el.dataset.id)
        .join(",")}`
    )
      .then((response) => response.text())
      .then((data) => {
        if (data.trim()) {
          photoList.insertAdjacentHTML("beforeend", data);
          currentPage++;
          loadMoreBtn.textContent = "Charger plus";
          loading = false;
        } else {
          // Plus de photos → on supprime le bouton
          loadMoreBtn.style.display = "none";
        }
      })
      .catch((error) => {
        console.error("Erreur AJAX :", error);
        loadMoreBtn.textContent = "Erreur";
      });
  });
});
