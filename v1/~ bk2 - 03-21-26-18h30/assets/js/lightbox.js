(function (window, document) {
  function initLightbox(root) {
    const scope = root || document;
    const lightbox = scope.querySelector('[data-lightbox]');
    const lightboxImage = scope.querySelector('[data-lightbox-output]');
    const lightboxCaption = scope.querySelector('[data-lightbox-caption-output]');
    const lightboxCloseButtons = scope.querySelectorAll('[data-lightbox-close]');
    const lightboxPrevButton = scope.querySelector('[data-lightbox-prev]');
    const lightboxNextButton = scope.querySelector('[data-lightbox-next]');

    let lightboxItems = [];
    let lightboxIndex = -1;

    const isVisibleItem = (el) => {
      if (!el) return false;
      if (el.hidden) return false;
      if (el.classList.contains('is-hidden')) return false;
      if (el.closest('[hidden]')) return false;
      return true;
    };

    const renderLightboxItem = (index) => {
      if (!lightbox || !lightboxImage || !lightboxItems.length || index < 0 || index >= lightboxItems.length) return;

      lightboxIndex = index;
      const trigger = lightboxItems[lightboxIndex];
      const src = trigger.getAttribute('data-lightbox-image') || '';
      if (!src) return;

      lightboxImage.src = src;
      lightboxImage.alt = trigger.getAttribute('data-lightbox-alt') || trigger.getAttribute('data-lightbox-title') || '';

      if (lightboxCaption) {
        lightboxCaption.textContent = trigger.getAttribute('data-lightbox-caption') || '';
      }

      const hasMultiple = lightboxItems.length > 1;
      if (lightboxPrevButton) {
        lightboxPrevButton.hidden = !hasMultiple;
        lightboxPrevButton.disabled = !hasMultiple;
      }
      if (lightboxNextButton) {
        lightboxNextButton.hidden = !hasMultiple;
        lightboxNextButton.disabled = !hasMultiple;
      }
    };

    const open = (index) => {
      if (!lightbox || !lightboxImage) return;
      renderLightboxItem(index);
      lightbox.hidden = false;
      document.body.classList.add('lightbox-is-open');
    };

    const close = () => {
      if (!lightbox || !lightboxImage) return;
      lightbox.hidden = true;
      lightboxImage.src = '';
      lightboxImage.alt = '';
      if (lightboxCaption) {
        lightboxCaption.textContent = '';
      }
      lightboxItems = [];
      lightboxIndex = -1;
      document.body.classList.remove('lightbox-is-open');
    };

    const showPrevious = () => {
      if (!lightboxItems.length) return;
      const nextIndex = lightboxIndex <= 0 ? lightboxItems.length - 1 : lightboxIndex - 1;
      renderLightboxItem(nextIndex);
    };

    const showNext = () => {
      if (!lightboxItems.length) return;
      const nextIndex = lightboxIndex >= lightboxItems.length - 1 ? 0 : lightboxIndex + 1;
      renderLightboxItem(nextIndex);
    };

    const bindTriggers = (bindScope) => {
      const triggerScope = bindScope || scope;
      if (!triggerScope) return;

      const triggers = triggerScope.querySelectorAll('[data-lightbox-image]');
      triggers.forEach((trigger) => {
        if (trigger.dataset.lightboxBound === 'true') return;

        trigger.dataset.lightboxBound = 'true';
        trigger.addEventListener('click', (event) => {
          event.preventDefault();
          event.stopPropagation();

          const panel = trigger.closest('[data-gallery-panel]');
          const allTriggers = panel
            ? Array.from(panel.querySelectorAll('[data-lightbox-image]')).filter(isVisibleItem)
            : [trigger];

          lightboxItems = allTriggers.length ? allTriggers : [trigger];
          const clickedIndex = lightboxItems.indexOf(trigger);
          open(clickedIndex >= 0 ? clickedIndex : 0);
        });
      });
    };

    if (!lightbox || !lightboxImage) {
      return { bindTriggers: function () {} };
    }

    lightboxCloseButtons.forEach((button) => {
      button.addEventListener('click', close);
    });

    lightbox.addEventListener('click', (event) => {
      if (event.target === lightbox) close();
    });

    if (lightboxPrevButton) {
      lightboxPrevButton.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        showPrevious();
      });
    }

    if (lightboxNextButton) {
      lightboxNextButton.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        showNext();
      });
    }

    document.addEventListener('keydown', (event) => {
      if (!lightbox || lightbox.hidden) return;
      if (event.key === 'Escape') {
        event.preventDefault();
        close();
      }
      if (event.key === 'ArrowLeft') {
        event.preventDefault();
        showPrevious();
      }
      if (event.key === 'ArrowRight') {
        event.preventDefault();
        showNext();
      }
    });

    return { bindTriggers, open, close };
  }

  window.initLightbox = initLightbox;
})(window, document);
