(function (window, document) {
  function initTrackTabs(root) {
    const scope = root || document;
    const groups = scope.querySelectorAll('[data-track-tabs]');

    groups.forEach((group) => {
      const buttons = Array.from(group.querySelectorAll('.track-tabs__button'));
      const panels = Array.from(group.querySelectorAll('.track-tabs__panel'));

      buttons.forEach((button) => {
        button.addEventListener('click', () => {
          const targetId = button.getAttribute('data-tab-target');
          const targetPanel = targetId ? group.querySelector('#' + targetId) : null;

          buttons.forEach((btn) => {
            btn.classList.remove('is-active');
            btn.setAttribute('aria-selected', 'false');
          });

          panels.forEach((panel) => {
            panel.hidden = true;
          });

          button.classList.add('is-active');
          button.setAttribute('aria-selected', 'true');

          if (targetPanel) {
            targetPanel.hidden = false;
          }
        });
      });
    });
  }

  function initGenericTabs(root) {
    const scope = root || document;
    const buttons = scope.querySelectorAll('[data-tab-button]');

    buttons.forEach((button) => {
      button.addEventListener('click', () => {
        const group = button.getAttribute('data-tab-button');
        const target = button.getAttribute('data-tab-target');
        if (!group || !target) return;

        const groupButtons = scope.querySelectorAll('[data-tab-button="' + group + '"]');
        const groupPanels = scope.querySelectorAll('[data-tab-panel="' + group + '"]');

        groupButtons.forEach((btn) => {
          const isActive = btn === button;
          btn.classList.toggle('is-active', isActive);
          btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });

        groupPanels.forEach((panel) => {
          const matches = panel.getAttribute('data-tab-id') === target;
          panel.hidden = !matches;
        });
      });
    });
  }

  window.initTrackTabs = initTrackTabs;
  window.initGenericTabs = initGenericTabs;
})(window, document);
