(function (window, document) {
  function initTrackListToggle(root) {
    const scope = root || document;
    const toggles = scope.querySelectorAll('[data-track-toggle]');

    toggles.forEach((toggle) => {
      if (toggle.dataset.trackToggleBound === 'true') return;
      toggle.dataset.trackToggleBound = 'true';

      const panel = toggle.closest('.track-tabs__panel');
      if (!panel) return;

      const list =
        panel.querySelector('[data-track-list]') ||
        panel.querySelector('.track-list');

      if (!list) return;

      const updateToggleState = () => {
        const isCollapsed = list.classList.contains('is-collapsed');
        toggle.textContent = isCollapsed ? 'Expand All' : 'Collapse All';
        toggle.setAttribute('aria-expanded', isCollapsed ? 'false' : 'true');
      };

      updateToggleState();

      toggle.addEventListener('click', () => {
        list.classList.toggle('is-collapsed');
        updateToggleState();
      });
    });
  }

  function initGalleryTabs(root) {
    const scope = root || document;
    const galleryGroups = scope.querySelectorAll('[data-gallery-tabs]');

    galleryGroups.forEach((group) => {
      const tabButtons = Array.from(group.querySelectorAll('[data-gallery-tab]'));
      const panels = Array.from(group.querySelectorAll('[data-gallery-panel]'));
      const initialCount = parseInt(group.getAttribute('data-initial-count') || '12', 10);

      if (!tabButtons.length || !panels.length) return;

      const showPanel = (targetId) => {
        tabButtons.forEach((btn) => {
          const isActive = btn.getAttribute('data-gallery-tab') === targetId;
          btn.classList.toggle('is-active', isActive);
          btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
          btn.setAttribute('tabindex', isActive ? '0' : '-1');
        });

        panels.forEach((panel) => {
          const panelId = panel.getAttribute('data-gallery-panel');
          const isVisible = panelId === targetId;
          panel.hidden = !isVisible;

          if (!isVisible) return;

          const items = Array.from(panel.querySelectorAll('[data-gallery-item]'));
          const toggle = panel.querySelector('[data-gallery-toggle]');
          const expanded = toggle ? toggle.getAttribute('data-expanded') === 'true' : false;

          items.forEach((item, idx) => {
            item.classList.toggle('is-hidden', !expanded && idx >= initialCount);
          });

          if (toggle) {
            const needsToggle = items.length > initialCount;
            toggle.hidden = !needsToggle;
            toggle.textContent = expanded ? 'Collapse' : 'Show More';
            toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
          }
        });
      };

      tabButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
          const targetId = btn.getAttribute('data-gallery-tab');
          if (!targetId) return;
          showPanel(targetId);
        });
      });

      panels.forEach((panel) => {
        const toggle = panel.querySelector('[data-gallery-toggle]');
        if (!toggle || toggle.dataset.galleryToggleBound === 'true') return;

        toggle.dataset.galleryToggleBound = 'true';

        toggle.addEventListener('click', () => {
          const expanded = toggle.getAttribute('data-expanded') === 'true';
          const newExpanded = !expanded;

          toggle.setAttribute('data-expanded', newExpanded ? 'true' : 'false');
          toggle.setAttribute('aria-expanded', newExpanded ? 'true' : 'false');

          const items = Array.from(panel.querySelectorAll('[data-gallery-item]'));
          items.forEach((item, idx) => {
            item.classList.toggle('is-hidden', !newExpanded && idx >= initialCount);
          });

          toggle.textContent = newExpanded ? 'Collapse' : 'Show More';
        });
      });

      const firstActive = group.querySelector('[data-gallery-tab].is-active') || tabButtons[0];
      const firstId = firstActive ? firstActive.getAttribute('data-gallery-tab') : null;
      if (firstId) {
        showPanel(firstId);
      }
    });
  }

  window.initTrackListToggle = initTrackListToggle;
  window.initGalleryTabs = initGalleryTabs;
})(window, document);