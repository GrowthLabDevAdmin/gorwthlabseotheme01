const ctaBoxes = document.querySelectorAll(".cta-box.float");
const contactFormFooterWrapper = document.querySelector(
  ".contact-form-footer__wrapper"
);

function setBoxPosition() {
  ctaBoxes.forEach((element) => {
    const box = element.querySelector(".cta-box__box");
    if (!box) return;

    const boxHeight = box.offsetHeight;
    const topPosition = boxHeight * 0.55;
    const bottomPosition = boxHeight * 0.45;

    const firstChild = element.firstElementChild;
    const prevSibling = element.previousElementSibling;
    const nextSibling = element.nextElementSibling;

    if (firstChild) {
      firstChild.style.marginTop = `${-topPosition}px`;
      firstChild.style.paddingBottom = `${topPosition}px`;
    }

    if (prevSibling) {
      prevSibling.style.paddingBottom = `${topPosition + 70}px`;
      prevSibling.style.borderBottom = "8px solid rgb(var(--tertiary))";
    }

    if (nextSibling) {
      nextSibling.style.marginTop = `${-boxHeight}px`;

      const isFooter = nextSibling.classList.contains("site-footer");
      const paddingTop = `${bottomPosition + 60}px`;

      if (isFooter && contactFormFooterWrapper) {
        contactFormFooterWrapper.style.paddingTop = paddingTop;
      } else {
        nextSibling.style.paddingTop = paddingTop;
      }
    }
  });
}

// ResizeObserver watches specific elements instead of entire window
const resizeObserver = new ResizeObserver(() => {
  setBoxPosition();
});

// Observe each cta-box
ctaBoxes.forEach((box) => resizeObserver.observe(box));

// Initial call
setBoxPosition();
