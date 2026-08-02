document.addEventListener('DOMContentLoaded', function () {
  function initSafeTrackTabs(root) {
    var scope = root || document;
    var groups = scope.querySelectorAll('[data-track-tabs]');

    groups.forEach(function (group) {
      var buttons = Array.prototype.slice.call(
        group.querySelectorAll('.track-tabs__button')
      );
      var panels = Array.prototype.slice.call(
        group.querySelectorAll('.track-tabs__panel')
      );

      if (!buttons.length || !panels.length) {
        return;
      }

      function getPanelForButton(button, fallbackIndex) {
        var controls = button.getAttribute('aria-controls');

        if (controls) {
          var controlledPanel = group.querySelector('#' + CSS.escape(controls));
          if (controlledPanel) {
            return controlledPanel;
          }
        }

        return panels[fallbackIndex] || null;
      }

      function activateTab(nextButton) {
        buttons.forEach(function (button, index) {
          var isActive = button === nextButton;
          var panel = getPanelForButton(button, index);

          button.classList.toggle('is-active', isActive);
          button.setAttribute('aria-selected', isActive ? 'true' : 'false');
          button.setAttribute('tabindex', isActive ? '0' : '-1');

          if (panel) {
            panel.classList.toggle('is-active', isActive);

            if (isActive) {
              panel.removeAttribute('hidden');
            } else {
              panel.setAttribute('hidden', '');
            }
          }
        });
      }

      buttons.forEach(function (button) {
        button.addEventListener('click', function () {
          activateTab(button);
        });

        button.addEventListener('keydown', function (event) {
          var currentIndex = buttons.indexOf(button);
          var nextIndex = currentIndex;

          if (event.key === 'ArrowRight') {
            nextIndex = (currentIndex + 1) % buttons.length;
          } else if (event.key === 'ArrowLeft') {
            nextIndex = (currentIndex - 1 + buttons.length) % buttons.length;
          } else {
            return;
          }

          event.preventDefault();
          buttons[nextIndex].focus();
          activateTab(buttons[nextIndex]);
        });
      });

      var initialButton =
        group.querySelector('.track-tabs__button.is-active') ||
        group.querySelector('.track-tabs__button[aria-selected="true"]') ||
        buttons[0];

      activateTab(initialButton);
    });
  }

  function initMerchLightbox(root) {
    var scope = root || document;
    var modal = scope.getElementById('merch-lightbox');

    if (!modal) {
      return;
    }

    var image = modal.querySelector('#merch-lightbox-image');
    var eyebrow = modal.querySelector('#merch-lightbox-eyebrow');
    var title = modal.querySelector('#merch-lightbox-title');
    var description = modal.querySelector('#merch-lightbox-description');
    var link = modal.querySelector('#merch-lightbox-link');
    var closeButtons = modal.querySelectorAll('[data-merch-lightbox-close]');
    var triggers = scope.querySelectorAll('[data-merch-lightbox-trigger]');
    var lastTrigger = null;

    function closeMerchLightbox() {
      modal.setAttribute('hidden', '');
      modal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('is-merch-lightbox-open');

      if (lastTrigger) {
        lastTrigger.focus();
      }
    }

    function openMerchLightbox(trigger) {
      lastTrigger = trigger;

      image.src = trigger.getAttribute('data-merch-image') || '';
      image.alt = trigger.getAttribute('data-merch-alt') || '';

      eyebrow.textContent = trigger.getAttribute('data-merch-eyebrow') || '';
      title.textContent = trigger.getAttribute('data-merch-title') || '';
      description.textContent =
        trigger.getAttribute('data-merch-description') || '';

      var href = trigger.getAttribute('data-merch-link') || '#';
      var label = trigger.getAttribute('data-merch-link-label') || 'Preorder';

      link.textContent = label;
      link.setAttribute('href', href);

      if (href === '#' || href === '') {
        link.onclick = function (event) {
          event.preventDefault();
        };
        link.classList.add('is-disabled');
      } else {
        link.onclick = null;
        link.classList.remove('is-disabled');
      }

      modal.removeAttribute('hidden');
      modal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('is-merch-lightbox-open');
    }

    triggers.forEach(function (trigger) {
      trigger.addEventListener('click', function () {
        openMerchLightbox(trigger);
      });
    });

    closeButtons.forEach(function (button) {
      button.addEventListener('click', closeMerchLightbox);
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && !modal.hasAttribute('hidden')) {
        closeMerchLightbox();
      }
    });
  }

  function initBackToTop(root) {
    var scope = root || document;
    var button = scope.querySelector('[data-back-to-top]');

    if (!button) {
      return;
    }

    function toggleBackToTop() {
      var shouldShow = window.scrollY > 480;

      button.hidden = !shouldShow;
      button.classList.toggle('is-visible', shouldShow);
      button.setAttribute('aria-hidden', shouldShow ? 'false' : 'true');
    }

    button.addEventListener('click', function () {
      var prefersReducedMotion =
        window.matchMedia &&
        window.matchMedia('(prefers-reduced-motion: reduce)').matches;

      window.scrollTo({
        top: 0,
        behavior: prefersReducedMotion ? 'auto' : 'smooth'
      });
    });

    window.addEventListener('scroll', toggleBackToTop);
    window.addEventListener('resize', toggleBackToTop);

    toggleBackToTop();
  }

  function initScrollProgressBar() {
    var root = document.documentElement;
    var ticking = false;

    function updateScrollProgress() {
      var scrollTop = window.pageYOffset || root.scrollTop || 0;
      var scrollHeight = Math.max(
        document.body.scrollHeight,
        document.documentElement.scrollHeight
      );
      var clientHeight = window.innerHeight || document.documentElement.clientHeight;
      var maxScroll = Math.max(scrollHeight - clientHeight, 1);
      var progress = Math.min(Math.max(scrollTop / maxScroll, 0), 1);

      root.style.setProperty('--scroll-progress', progress.toFixed(4));
      ticking = false;
    }

    function requestUpdate() {
      if (ticking) {
        return;
      }

      ticking = true;
      window.requestAnimationFrame(updateScrollProgress);
    }

    window.addEventListener('scroll', requestUpdate, { passive: true });
    window.addEventListener('resize', requestUpdate);

    updateScrollProgress();
  }

  initSafeTrackTabs(document);
  initMerchLightbox(document);
  initBackToTop(document);
  initScrollProgressBar(document);
  if (typeof window.initGenericTabs === 'function') {
    window.initGenericTabs();
  }

  var navLinks = [];
  if (typeof window.initMobileMenu === 'function') {
    navLinks = window.initMobileMenu() || [];
  }

  if (typeof window.initStickyHeader === 'function') {
    window.initStickyHeader();
  }

  if (typeof window.initActiveNav === 'function') {
    window.initActiveNav(navLinks);
  }

  if (typeof window.initGlobalExternalLinks === 'function') {
    window.initGlobalExternalLinks();
  }

  if (typeof window.initTrackListToggle === 'function') {
    window.initTrackListToggle();
  }

  if (typeof window.initGalleryTabs === 'function') {
    window.initGalleryTabs();
  }

  var lightboxApi = null;
  if (typeof window.initLightbox === 'function') {
    lightboxApi = window.initLightbox();
    if (lightboxApi && typeof lightboxApi.bindTriggers === 'function') {
      lightboxApi.bindTriggers(document);
    }
  }

  if (typeof window.initVideoModal === 'function') {
    window.initVideoModal();
  }
});

function initViewportStateReset() {
  var header = document.querySelector('.site-header');
  var nav = document.querySelector('.site-nav');
  var toggle = document.querySelector('.menu-toggle');

  function resetViewportState() {
    if (nav) {
      nav.classList.remove('is-open');
    }

    if (header) {
      header.classList.remove('is-menu-open');
    }

    if (toggle) {
      toggle.setAttribute('aria-expanded', 'false');
    }

    document.body.classList.remove('is-menu-open');
    document.documentElement.scrollLeft = 0;
    document.body.scrollLeft = 0;

    if (window.scrollX !== 0) {
      window.scrollTo(window.scrollY ? { top: window.scrollY, left: 0, behavior: 'auto' } : 0, 0);
    }
  }

  window.addEventListener('resize', resetViewportState);
  window.addEventListener('orientationchange', resetViewportState);
  window.addEventListener('hashchange', resetViewportState);

  resetViewportState();
}