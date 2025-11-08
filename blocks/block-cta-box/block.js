const ctaBox = document.querySelectorAll(".cta-box.float");
const ctaBoxWrapper = document.querySelectorAll(
  ".cta-box.float .cta-box__wrapper"
);
const contactFormFooterWrapper = document.querySelector(
  ".contact-form-footer__wrapper"
);

document.addEventListener("DOMContentLoaded", () => {
  setBoxPosition();

  window.addEventListener("resize", () => {
    setBoxPosition();
  });
});

function setBoxPosition() {
  let resizeTimer;
  clearTimeout(resizeTimer);

  resizeTimer = setTimeout(() => {
    ctaBox.forEach((element) => {
      let boxHeight = element.querySelector(".cta-box__box").offsetHeight;

      let topPosition = boxHeight * 0.55;
      let bottomPosition = boxHeight * 0.45;

      element.firstElementChild.style.marginTop = -topPosition + "px";
      element.firstElementChild.style.paddingBottom = topPosition + "px";

      element.previousElementSibling.style.paddingBottom =
        topPosition + 70 + "px";
      element.previousElementSibling.style.borderBottom =
        "8px solid rgb(var(--tertiary))";

      element.nextElementSibling.style.marginTop = -boxHeight + "px";

      if (
        element.nextElementSibling.classList.contains("site-footer") &&
        contactFormFooterWrapper &&
        ctaBox
      ) {
        contactFormFooterWrapper.style.paddingTop = bottomPosition + 60 + "px";
      } else {
        element.nextElementSibling.style.paddingTop =
          bottomPosition + 60 + "px";
      }
    });
  }, 250);
}
