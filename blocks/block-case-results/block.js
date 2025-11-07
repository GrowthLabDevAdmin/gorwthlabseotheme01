document.addEventListener("DOMContentLoaded", () => {
  var resultsCarousel = new Splide(".case-results__carousel .splide", {
    type: "loop",
    perPage: 3,
    perMove: 1,
    arrows: true,
    pagination: false,
    breakpoints: {
      [tablet]: {
        perPage: 1,
      },
      [ldpi]: {
        perPage: 2,
      },
    },
  });

  resultsCarousel.mount();
});
