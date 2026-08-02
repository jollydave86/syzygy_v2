(function (window, document) {
  function initMobileMenu() {
    const menuToggle = document.querySelector('.menu-toggle');
    const siteNav = document.querySelector('.site-nav');
    const navLinks = document.querySelectorAll('.site-nav__link');

    if (!menuToggle || !siteNav) return navLinks;

    menuToggle.addEventListener('click', () => {
      const isOpen = siteNav.classList.toggle('is-open');
      menuToggle.classList.toggle('is-active', isOpen);
      menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      document.body.classList.toggle('menu-is-open', isOpen);
    });

    navLinks.forEach((link) => {
      link.addEventListener('click', () => {
        siteNav.classList.remove('is-open');
        menuToggle.classList.remove('is-active');
        menuToggle.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('menu-is-open');
      });
    });

    return navLinks;
  }

  function initStickyHeader() {
    const header = document.querySelector('.site-header');
    if (!header) return;

    const updateHeaderState = () => {
      header.classList.toggle('is-scrolled', window.scrollY > 24);
    };

    updateHeaderState();
    window.addEventListener('scroll', updateHeaderState, { passive: true });
  }

  function initActiveNav(navLinks) {
    if (!navLinks || !navLinks.length || typeof window.IntersectionObserver !== 'function') return;

    const sectionMap = [];
    navLinks.forEach((link) => {
      const href = link.getAttribute('href');
      if (!href || !href.startsWith('#')) return;

      const section = document.querySelector(href);
      if (!section) return;

      sectionMap.push({ link, section });
    });

    if (!sectionMap.length) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;

          navLinks.forEach((link) => link.classList.remove('is-current'));
          const match = sectionMap.find((item) => item.section === entry.target);
          if (match) {
            match.link.classList.add('is-current');
          }
        });
      },
      { rootMargin: '-35% 0px -45% 0px', threshold: 0 }
    );

    sectionMap.forEach((item) => observer.observe(item.section));
  }

  function initGlobalExternalLinks() {
    const links = document.querySelectorAll('a[href]');
    links.forEach((link) => {
      const href = link.getAttribute('href');
      if (!href) return;

      if (href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('javascript:')) {
        return;
      }

      try {
        const url = new URL(href, window.location.href);
        if (url.origin !== window.location.origin) {
          link.setAttribute('target', '_blank');
          link.setAttribute('rel', 'noopener noreferrer');
        }
      } catch (error) {
        // ignore invalid URLs
      }
    });
  }

  window.initMobileMenu = initMobileMenu;
  window.initStickyHeader = initStickyHeader;
  window.initActiveNav = initActiveNav;
  window.initGlobalExternalLinks = initGlobalExternalLinks;
})(window, document);
