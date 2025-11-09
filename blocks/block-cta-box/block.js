const ctaBoxes = document.querySelectorAll(".cta-box.float");
const contactFormFooterWrapper = document.querySelector(
  ".contact-form-footer__wrapper"
);

function setBoxPosition() {
  // Batch all measurements (single reflow)
  const measurements = Array.from(ctaBoxes).map((element) => {
    const box = element.querySelector(".cta-box__box");
    return {
      element,
      box,
      boxHeight: box?.offsetHeight || 0,
      firstChild: element.firstElementChild,
      prevSibling: element.previousElementSibling,
      nextSibling: element.nextElementSibling,
    };
  });

  // Batch all style updates (single repaint)
  requestAnimationFrame(() => {
    measurements.forEach(
      ({ element, box, boxHeight, firstChild, prevSibling, nextSibling }) => {
        if (!box || boxHeight === 0) return;

        const topPosition = boxHeight * 0.55;
        const bottomPosition = boxHeight * 0.45;

        if (firstChild) {
          firstChild.style.cssText += `margin-top:${-topPosition}px;padding-bottom:${topPosition}px;`;
        }

        if (prevSibling) {
          prevSibling.style.cssText += `padding-bottom:${
            topPosition + 70
          }px;border-bottom:8px solid rgb(var(--tertiary));`;
        }

        if (nextSibling) {
          const isFooter = nextSibling.classList.contains("site-footer");
          const paddingTop = `${bottomPosition + 60}px`;

          nextSibling.style.marginTop = `${-boxHeight}px`;

          if (isFooter && contactFormFooterWrapper) {
            contactFormFooterWrapper.style.paddingTop = paddingTop;
          } else {
            nextSibling.style.paddingTop = paddingTop;
          }
        }
      }
    );
  });
}

// CRITICAL: Run immediately - don't wait for anything
setBoxPosition();

// Also run when DOM is ready (in case elements weren't available yet)
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", setBoxPosition);
} else {
  setBoxPosition();
}

// Run after all resources load (images may affect height)
window.addEventListener("load", setBoxPosition);

// Debounced resize handler
let resizeTimer;
window.addEventListener(
  "resize",
  () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(setBoxPosition, 100);
  },
  { passive: true }
);
