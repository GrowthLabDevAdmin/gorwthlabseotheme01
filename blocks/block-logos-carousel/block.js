document.addEventListener("DOMContentLoaded", () => {
  if (document.querySelector(".logos-carousel__carousel")) {
    var logosCarousel = new Splide(".logos-carousel__carousel .splide", {
      type: "loop",
      perPage: 7,
      perMove: 1,
      arrows: false,
      pagination: false,
      breakpoints: {
        [tablet]: {
          perPage: 3,
        },
        [ldpi]: {
          perPage: 5,
        },
      },
    });

    logosCarousel.mount();
  }
});
