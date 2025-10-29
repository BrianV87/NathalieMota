window.addEventListener("DOMContentLoaded", function () {
  // Récupération des éléments : zone "flèches" et miniature associée
  const arrows = document.querySelector(".second-arrows");
  const thumb = document.querySelector(".second-thumb");

  // On ne branche les events que si les deux existent
  if (arrows && thumb) {
    // Affiche la miniature au survol des flèches
    arrows.addEventListener("mouseover", function () {
      thumb.style.display = "block";
    });

    // Cache la miniature quand on quitte la zone
    arrows.addEventListener("mouseout", function () {
      thumb.style.display = "none";
    });
  }
});
