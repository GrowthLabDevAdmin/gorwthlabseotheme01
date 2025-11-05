const siteHeader = document.querySelector(".site-header");
const mobileBtn = document.querySelector(".mobile-menu-button");
const mainMenu = document.querySelector(".site-header .main-nav");
const parentMenuItems = document.querySelectorAll(
  ".site-header .main-nav .menu-item-has-children"
);

//Breakpoints
const mobile = 480;
const tablet = 768;
const ldpi = 1024;
const mdpi = 1200;
const hdpi = 1440;

document.addEventListener("DOMContentLoaded", () => {
  eventListeners();

  splideCarousels();
});

function eventListeners() {
  showMenus();

  // Debounce resize event
  let resizeTimer;
  window.addEventListener("resize", () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      showMenus();
    }, 250);
  });

  if (document.querySelector(".site-header--sticky"))
    window.addEventListener("scroll", fadeInHeader);
}

function showMenus() {
  // re-query in case the DOM changed
  const parentMenuItems = document.querySelectorAll(
    ".site-header .main-nav .menu-item-has-children"
  );

  if (!mobileBtn || !mainMenu) return;

  // always remove listener using the same reference before adding
  mobileBtn.removeEventListener("click", handleMenuClick);

  if (window.screen.width > 768) {
    mobileBtn.classList.remove("active");
    mainMenu.classList.remove("active");

    // remove listeners on desktop
    parentMenuItems.forEach((item) => {
      item.removeEventListener("click", handleSubMenuClick);
      item.classList.remove("active");
    });
  } else {
    // add listener on mobile (same reference, no wrapper)
    mobileBtn.addEventListener("click", handleMenuClick);

    parentMenuItems.forEach((item) => {
      // ensure there are no duplicates
      item.removeEventListener("click", handleSubMenuClick);
      item.addEventListener("click", handleSubMenuClick);
    });
  }
}

// Function to handle menu item clicks
function handleMenuClick() {
  removeSubmenuActiveClasses();
  mainMenu.classList.toggle("active");
  mobileBtn.classList.toggle("active");
}

// Function to handle submenu item clicks
function handleSubMenuClick(e) {
  if (e.target.tagName !== "A") {
    e.stopPropagation();
    let currentItem = e.currentTarget;
    currentItem.classList.toggle("active");
  }
}

function removeSubmenuActiveClasses() {
  parentMenuItems.forEach((item) => {
    item.classList.remove("active");
  });
}

//Top Bar on Scroll
function fadeInHeader() {
  if (window.scrollY > 0) {
    siteHeader.classList.add("scrolling");
  } else {
    siteHeader.classList.remove("scrolling");
  }
}

//Splide Carousels
function splideCarousels() {
  var footerLocations = new Splide(".locations-cards__carousel .splide", {
    type: "loop",
    perPage: 4,
    perMove: 1,
    gap: "1.6rem",
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

  footerLocations.mount();
}
